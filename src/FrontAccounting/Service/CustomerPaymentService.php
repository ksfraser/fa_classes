<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\DTO\DebtorTransaction;
use FrontAccounting\Repository\DebtorTransactionRepository;

/**
 * Service for creating and retrieving FA customer payments.
 *
 * Orchestrates the customer payment workflow by wrapping the FA core
 * write_customer_payment() function. Provides read access to payment
 * transactions via the DTO/Repository layer.
 *
 * ┌────────────────────────────────────────────────────────────────┐
 * │                      CustomerPaymentService                    │
 * │  - debitTransRepo: DebtorTransactionRepository                 │
 * ├────────────────────────────────────────────────────────────────┤
 * │  + createPayment(CustomerPaymentRequest): int                  │
 * │  + getPayment(int $transNo): ?DebtorTransaction                │
 * │  + getCustomerTransactions(int $customerId): DebtorTransaction[]│
 * │  + getCurrentPaymentId(): ?int                                 │
 * ├────────────────────────────────────────────────────────────────┤
 * │ Creates a customer payment transaction using FA core            │
 * │ write_customer_payment() and retrieves existing payments        │
 * │ through the DTO/Repository layer.                               │
 * └────────────────────────────────────────────────────────────────┘
 *
 * Runtime dependencies (FA core functions available in module context):
 *   write_customer_payment() – sales/includes/db/payment_db.inc
 *   get_customer_trans()     – sales/includes/db/sales_db.inc
 */
class CustomerPaymentService
{
    private DebtorTransactionRepository $debitTransRepo;

    public function __construct(DebtorTransactionRepository $debitTransRepo)
    {
        $this->debitTransRepo = $debitTransRepo;
    }

    /**
     * Create a customer payment transaction.
     *
     * Delegates to the FA core write_customer_payment() function which
     * handles GL posting, bank transactions, allocations, and reference
     * management inside a database transaction.
     *
     * @param  CustomerPaymentRequest $request  All payment parameters
     * @return int                              The new payment transaction number
     */
    public function createPayment(CustomerPaymentRequest $request): int
    {
        return $this->callFaWriteCustomerPayment($request);
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

    // ──── FA core function wrapper (overridable in tests) ────

    /**
     * Wrap write_customer_payment() FA core function.
     *
     * @codeCoverageIgnore
     */
    protected function callFaWriteCustomerPayment(CustomerPaymentRequest $request): int
    {
        return \write_customer_payment(
            $request->transNo,
            $request->customerId,
            $request->branchId,
            $request->bankAccount,
            $request->date,
            $request->ref,
            $request->amount,
            $request->discount,
            $request->memo,
            $request->rate,
            $request->charge,
            $request->bankAmount
        );
    }
}
