<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\DTO\DebtorTransaction;
use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\Contracts\BankAccountInterface;
use FrontAccounting\Service\Contracts\BankTransInterface;
use FrontAccounting\Service\Contracts\CommentsInterface;
use FrontAccounting\Service\Contracts\CompanyPrefsInterface;
use FrontAccounting\Service\Contracts\CustomerInterface;
use FrontAccounting\Service\Contracts\DebtorTransInterface;
use FrontAccounting\Service\Contracts\ExchangeRateInterface;
use FrontAccounting\Service\Contracts\GlTransInterface;
use FrontAccounting\Service\Contracts\HooksInterface;
use FrontAccounting\Service\Contracts\MiscInterface;
use FrontAccounting\Service\Contracts\ReferenceInterface;
use FrontAccounting\Service\Contracts\TransactionInterface;

/**
 * @since 2026-07-09
 * Service for creating and retrieving FA customer payments.
 *
 * Orchestrates the full customer payment workflow: GL posting, bank
 * transactions, exchange-rate calculation, reference management, and
 * comment recording. Each sub-operation delegates to a dedicated Native
 * wrapper so individual FA core functions can be replaced later with
 * DTO/Repository-based implementations.
 *
 * ┌───────────────────────────────────────────────────────────────────┐
 * │                      CustomerPaymentService                       │
 * │  - debitTransRepo: DebtorTransactionRepository                    │
 * │  - config:         ServiceRuntimeConfig                           │
 * ├───────────────────────────────────────────────────────────────────┤
 * │  + createPayment(CustomerPaymentRequest): int                      │
 * │  + getPayment(int $transNo): ?DebtorTransaction                   │
 * │  + getCustomerTransactions(int $customerId): DebtorTransaction[]  │
 * ├───────────────────────────────────────────────────────────────────┤
 * │ Implements the same flow as FA core write_customer_payment():     │
 * │   1. begin_transaction                                            │
 * │   2. hook_db_prewrite                                             │
 * │   3. delete_comments + void_bank_trans (if editing)               │
 * │   4. get_bank_account + exchange rate calculation                 │
 * │   5. write_customer_trans                                         │
 * │   6. GL posting (bank, debtors, discount, charge, variance)       │
 * │   7. add_bank_trans                                               │
 * │   8. add_comments + Refs->save                                    │
 * │   9. hook_db_postwrite + commit_transaction                       │
 * └───────────────────────────────────────────────────────────────────┘
 *
 * Runtime dependencies (FA core functions available in module context):
 *   write_customer_payment() – sales/includes/db/payment_db.inc
 *   get_customer_trans()     – sales/includes/db/sales_db.inc
 */
class CustomerPaymentService
{
    private DebtorTransactionRepository $debitTransRepo;
    private ServiceRuntimeConfig $config;

    public function __construct(
        DebtorTransactionRepository $debitTransRepo,
        ServiceRuntimeConfig $config
    ) {
        $this->debitTransRepo = $debitTransRepo;
        $this->config = $config;
    }

    // ── internal convenience accessors ──────────────────────────────

    private function glTrans(): GlTransInterface { return $this->config->getGlTrans(); }
    private function bankTrans(): BankTransInterface { return $this->config->getBankTrans(); }
    private function debtorTrans(): DebtorTransInterface { return $this->config->getDebtorTrans(); }
    private function comments(): CommentsInterface { return $this->config->getComments(); }
    private function reference(): ReferenceInterface { return $this->config->getReference(); }
    private function bankAccount(): BankAccountInterface { return $this->config->getBankAccount(); }
    private function companyPrefs(): CompanyPrefsInterface { return $this->config->getCompanyPrefs(); }
    private function customer(): CustomerInterface { return $this->config->getCustomer(); }
    private function exchangeRate(): ExchangeRateInterface { return $this->config->getExchangeRate(); }
    private function hooks(): HooksInterface { return $this->config->getHooks(); }
    private function transaction(): TransactionInterface { return $this->config->getTransaction(); }
    private function misc(): MiscInterface { return $this->config->getMisc(); }

    /**
     * Create a customer payment transaction.
     *
     * Implements the full workflow matching FA core write_customer_payment():
     * database transaction, hooks, bank/GL posting, reference, and comments.
     *
     * @param  CustomerPaymentRequest $request  All payment parameters
     * @return int                              The new payment transaction number
     * @throws \Throwable                       On any failure (transaction committed)
     */
    public function createPayment(CustomerPaymentRequest $request): int
    {
        $this->transaction()->begin();
        $this->hooks()->preWrite($this, ST_CUSTPAYMENT);

        $companyPrefs = $this->companyPrefs()->getCompanyPrefs();

        if ($request->transNo !== 0) {
            $this->comments()->deleteComments(ST_CUSTPAYMENT, $request->transNo);
            $this->bankTrans()->voidBankTrans(ST_CUSTPAYMENT, $request->transNo, true);
        }

        $bank = $this->bankAccount()->getBankAccount($request->bankAccount);

        $bankAmount = $request->bankAmount;
        if ($bankAmount == 0.0) {
            $rate = $request->rate;
            if ($rate == 0.0) {
                $custCurrency = $this->customer()->getCustomerCurrency($request->customerId);
                $rate = $this->exchangeRate()->getExchangeRateFromTo(
                    $custCurrency,
                    $bank['bank_curr_code'],
                    $request->date
                );
            }
            $bankAmount = $request->amount / $rate;
        }

        $paymentNo = $this->debtorTrans()->writeCustomerTrans(
            ST_CUSTPAYMENT,
            $request->transNo,
            $request->customerId,
            $request->branchId,
            $request->date,
            $request->ref,
            $request->amount,
            $request->discount
        );

        $bankGlAccount = $this->bankAccount()->getBankGlAccount($request->bankAccount);

        $total = 0.0;

        $total += $this->glTrans()->addGlTrans(
            ST_CUSTPAYMENT, $paymentNo, $request->date,
            (string)$bankGlAccount, 0, 0, '',
            $bankAmount - $request->charge,
            $bank['bank_curr_code'], PT_CUSTOMER, $request->customerId
        );

        if ($request->branchId !== -1) {
            $branchData = $this->bankAccount()->getBranchAccounts($request->branchId);
            $debtorsAccount = $branchData['receivables_account'];
            $discountAccount = $branchData['payment_discount_account'];
        } else {
            $debtorsAccount = $companyPrefs['debtors_act'];
            $discountAccount = $companyPrefs['default_prompt_payment_act'];
        }

        if (($request->discount + $request->amount) != 0) {
            $total += $this->glTrans()->addGlTransCustomer(
                ST_CUSTPAYMENT, $paymentNo, $request->date,
                $debtorsAccount, 0, 0,
                -($request->discount + $request->amount),
                $request->customerId,
                'Cannot insert a GL transaction for the debtors account credit'
            );
        }

        if ($request->discount != 0) {
            $total += $this->glTrans()->addGlTransCustomer(
                ST_CUSTPAYMENT, $paymentNo, $request->date,
                $discountAccount, 0, 0,
                $request->discount,
                $request->customerId,
                'Cannot insert a GL transaction for the payment discount debit'
            );
        }

        if ($request->charge != 0) {
            $chargeAct = $this->companyPrefs()->getCompanyPref('bank_charge_act');
            $total += $this->glTrans()->addGlTrans(
                ST_CUSTPAYMENT, $paymentNo, $request->date,
                $chargeAct, 0, 0, '',
                $request->charge,
                $bank['bank_curr_code'], PT_CUSTOMER, $request->customerId
            );
        }

        if ($total != 0) {
            $varianceAct = $this->companyPrefs()->getCompanyPref('exchange_diff_act');
            $this->glTrans()->addGlTrans(
                ST_CUSTPAYMENT, $paymentNo, $request->date,
                $varianceAct, 0, 0, '',
                -$total, null, PT_CUSTOMER, $request->customerId
            );
        }

        $this->bankTrans()->addBankTrans(
            ST_CUSTPAYMENT, $paymentNo, $request->bankAccount,
            $request->ref, $request->date,
            $bankAmount - $request->charge,
            PT_CUSTOMER, $request->customerId
        );

        $this->comments()->addComments(ST_CUSTPAYMENT, $paymentNo, $request->date, $request->memo);
        $this->reference()->saveReference(ST_CUSTPAYMENT, $paymentNo, $request->ref);
        $this->hooks()->postWrite($this, ST_CUSTPAYMENT);
        $this->transaction()->commit();

        return $paymentNo;
    }

    /**
     * Retrieve an existing payment transaction by its transaction number.
     *
     * @param  int  $transNo  Payment transaction number
     * @return DebtorTransaction|null
     */
    public function getPayment(int $transNo): ?DebtorTransaction
    {
        return $this->debitTransRepo->findByTypeAndNo(ST_CUSTPAYMENT, $transNo);
    }

    /**
     * Retrieve all transactions (invoices, payments) for a given customer.
     *
     * @param  int  $customerId
     * @return DebtorTransaction[]
     */
    public function getCustomerTransactions(int $customerId): array
    {
        return $this->debitTransRepo->findByCustomer($customerId);
    }
}
