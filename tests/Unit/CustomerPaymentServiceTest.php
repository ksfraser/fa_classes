<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\DTO\DebtorTransaction;
use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\CustomerPaymentRequest;
use FrontAccounting\Service\CustomerPaymentService;
use FrontAccounting\Service\Native\BankAccountNative;
use FrontAccounting\Service\Native\BankTransNative;
use FrontAccounting\Service\Native\CommentsNative;
use FrontAccounting\Service\Native\CompanyPrefsNative;
use FrontAccounting\Service\Native\CustomerNative;
use FrontAccounting\Service\Native\DebtorTransNative;
use FrontAccounting\Service\Native\ExchangeRateNative;
use FrontAccounting\Service\Native\GlTransNative;
use FrontAccounting\Service\Native\HooksNative;
use FrontAccounting\Service\Native\MiscNative;
use FrontAccounting\Service\Native\ReferenceNative;
use FrontAccounting\Service\Native\TransactionNative;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerPaymentServiceTest extends TestCase
{
    private $glTrans;
    private $bankTrans;
    private $debtorTrans;
    private $comments;
    private $reference;
    private $bankAccount;
    private $companyPrefs;
    private $customer;
    private $exchangeRate;
    private $hooks;
    private $transaction;
    private $misc;
    private CustomerPaymentService $svc;

    protected function setUp(): void
    {
        $this->glTrans = $this->createMock(GlTransNative::class);
        $this->bankTrans = $this->createMock(BankTransNative::class);
        $this->debtorTrans = $this->createMock(DebtorTransNative::class);
        $this->comments = $this->createMock(CommentsNative::class);
        $this->reference = $this->createMock(ReferenceNative::class);
        $this->bankAccount = $this->createMock(BankAccountNative::class);
        $this->companyPrefs = $this->createMock(CompanyPrefsNative::class);
        $this->customer = $this->createMock(CustomerNative::class);
        $this->exchangeRate = $this->createMock(ExchangeRateNative::class);
        $this->hooks = $this->createMock(HooksNative::class);
        $this->transaction = $this->createMock(TransactionNative::class);
        $this->misc = $this->createMock(MiscNative::class);

        $db = new FakeDbAdapter([], 200);
        $repo = new DebtorTransactionRepository($db);

        $this->svc = new CustomerPaymentService(
            $repo,
            $this->glTrans,
            $this->bankTrans,
            $this->debtorTrans,
            $this->comments,
            $this->reference,
            $this->bankAccount,
            $this->companyPrefs,
            $this->customer,
            $this->exchangeRate,
            $this->hooks,
            $this->transaction,
            $this->misc
        );
    }

    public function testCreatePaymentFullFlow(): void
    {
        $request = new CustomerPaymentRequest(
            transNo: 0,
            customerId: 1,
            branchId: 1,
            bankAccount: 1,
            date: '2026-07-09',
            ref: 'PAY-001',
            amount: 100.00,
            discount: 0.0,
            memo: 'Test payment',
            rate: 0.0,
            charge: 0.0,
            bankAmount: 100.00,
        );

        $this->transaction->expects($this->once())->method('begin');
        $this->hooks->expects($this->once())->method('preWrite');

        $this->companyPrefs->expects($this->once())->method('getCompanyPrefs')
            ->willReturn([
                'debtors_act' => '1100',
                'default_prompt_payment_act' => '4205',
                'bank_charge_act' => '4500',
                'exchange_diff_act' => '4505',
            ]);

        $this->bankAccount->expects($this->once())->method('getBankAccount')
            ->with(1)
            ->willReturn(['bank_curr_code' => 'CAD']);

        $this->bankAccount->expects($this->once())->method('getBranchAccounts')
            ->with(1)
            ->willReturn([
                'receivables_account' => '1100',
                'payment_discount_account' => '4205',
            ]);

        $this->debtorTrans->expects($this->once())->method('writeCustomerTrans')
            ->willReturn(201);

        $this->bankAccount->expects($this->once())->method('getBankGlAccount')
            ->with(1)
            ->willReturn(1001);

        $this->glTrans->expects($this->once())->method('addGlTrans');
        $this->glTrans->expects($this->once())->method('addGlTransCustomer');

        $this->bankTrans->expects($this->once())->method('addBankTrans');

        $this->comments->expects($this->once())->method('addComments');
        $this->reference->expects($this->once())->method('saveReference');
        $this->hooks->expects($this->once())->method('postWrite');
        $this->transaction->expects($this->once())->method('commit');

        $result = $this->svc->createPayment($request);

        $this->assertSame(201, $result);
    }

    public function testCreatePaymentCalculatesBankAmountWhenZero(): void
    {
        $request = new CustomerPaymentRequest(
            transNo: 0,
            customerId: 1,
            branchId: 1,
            bankAccount: 1,
            date: '2026-07-09',
            ref: 'PAY-002',
            amount: 200.00,
            discount: 0.0,
            memo: '',
            rate: 1.3,
            charge: 0.0,
            bankAmount: 0.0,
        );

        $this->transaction->expects($this->once())->method('begin');
        $this->hooks->expects($this->once())->method('preWrite');

        $this->companyPrefs->expects($this->once())->method('getCompanyPrefs')
            ->willReturn(['debtors_act' => '1100', 'default_prompt_payment_act' => '4205']);

        $this->bankAccount->expects($this->once())->method('getBankAccount')
            ->willReturn(['bank_curr_code' => 'CAD']);

        $this->bankAccount->expects($this->once())->method('getBranchAccounts')
            ->with(1)
            ->willReturn([
                'receivables_account' => '1100',
                'payment_discount_account' => '4205',
            ]);

        $this->debtorTrans->expects($this->once())->method('writeCustomerTrans')
            ->willReturn(202);

        $this->bankAccount->expects($this->once())->method('getBankGlAccount')
            ->willReturn(1001);

        $this->glTrans->expects($this->any())->method('addGlTrans')->willReturn(0.0);
        $this->glTrans->expects($this->any())->method('addGlTransCustomer')->willReturn(0.0);
        $this->bankTrans->expects($this->once())->method('addBankTrans');
        $this->comments->expects($this->once())->method('addComments');
        $this->reference->expects($this->once())->method('saveReference');
        $this->hooks->expects($this->once())->method('postWrite');
        $this->transaction->expects($this->once())->method('commit');

        $result = $this->svc->createPayment($request);

        $this->assertSame(202, $result);
    }

    public function testCreatePaymentWithDiscountAndCharge(): void
    {
        $request = new CustomerPaymentRequest(
            transNo: 0,
            customerId: 1,
            branchId: 1,
            bankAccount: 1,
            date: '2026-07-09',
            ref: 'PAY-003',
            amount: 500.00,
            discount: 10.00,
            memo: 'With discount',
            rate: 0.0,
            charge: 5.00,
            bankAmount: 0.0,
        );

        $this->transaction->expects($this->once())->method('begin');
        $this->hooks->expects($this->once())->method('preWrite');

        $this->companyPrefs->expects($this->once())->method('getCompanyPrefs')
            ->willReturn(['debtors_act' => '1100', 'default_prompt_payment_act' => '4205']);

        $this->bankAccount->expects($this->once())->method('getBankAccount')
            ->willReturn(['bank_curr_code' => 'CAD']);

        $this->bankAccount->expects($this->once())->method('getBranchAccounts')
            ->with(1)
            ->willReturn([
                'receivables_account' => '1100',
                'payment_discount_account' => '4205',
            ]);

        $this->customer->expects($this->once())->method('getCustomerCurrency')
            ->willReturn('USD');

        $this->exchangeRate->expects($this->once())->method('getExchangeRateFromTo')
            ->with('USD', 'CAD', '2026-07-09')
            ->willReturn(1.3);

        $this->debtorTrans->expects($this->once())->method('writeCustomerTrans')
            ->willReturn(203);

        $this->bankAccount->expects($this->once())->method('getBankGlAccount')
            ->willReturn(1001);

        $this->glTrans->expects($this->exactly(2))->method('addGlTrans')
            ->willReturn(0.0);
        $this->glTrans->expects($this->exactly(2))->method('addGlTransCustomer')
            ->willReturn(0.0);

        $this->bankTrans->expects($this->once())->method('addBankTrans');
        $this->comments->expects($this->once())->method('addComments');
        $this->reference->expects($this->once())->method('saveReference');
        $this->hooks->expects($this->once())->method('postWrite');
        $this->transaction->expects($this->once())->method('commit');

        $result = $this->svc->createPayment($request);

        $this->assertSame(203, $result);
    }

    public function testGetPaymentDelegatesToRepository(): void
    {
        $glTrans = $this->createMock(GlTransNative::class);
        $bankTrans = $this->createMock(BankTransNative::class);
        $debtorTrans = $this->createMock(DebtorTransNative::class);
        $comments = $this->createMock(CommentsNative::class);
        $reference = $this->createMock(ReferenceNative::class);
        $bankAccount = $this->createMock(BankAccountNative::class);
        $companyPrefs = $this->createMock(CompanyPrefsNative::class);
        $customer = $this->createMock(CustomerNative::class);
        $exchangeRate = $this->createMock(ExchangeRateNative::class);
        $hooks = $this->createMock(HooksNative::class);
        $transaction = $this->createMock(TransactionNative::class);
        $misc = $this->createMock(MiscNative::class);

        $db = new FakeDbAdapter([[
            'trans_no' => '201',
            'type' => (string)ST_CUSTPAYMENT,
            'debtor_no' => '1',
            'branch_code' => '1',
            'tran_date' => '2026-07-09',
            'due_date' => '2026-07-09',
            'reference' => 'PAY-001',
            'order_' => '0',
            'ov_amount' => '100.00',
            'ov_gst' => '0.00',
            'ov_freight' => '0.00',
            'ov_freight_tax' => '0.00',
            'ov_discount' => '0.00',
            'alloc' => '100.00',
            'prep_amount' => '0.00',
            'rate' => '1.00000',
            'ship_via' => '0',
            'dimension_id' => '0',
            'dimension2_id' => '0',
            'payment_terms' => '0',
            'tax_included' => '0',
        ]], 1);

        $svc = new CustomerPaymentService(
            new DebtorTransactionRepository($db),
            $glTrans, $bankTrans, $debtorTrans, $comments, $reference,
            $bankAccount, $companyPrefs, $customer, $exchangeRate,
            $hooks, $transaction, $misc
        );

        $result = $svc->getPayment(201);

        $this->assertNotNull($result);
        $this->assertSame(201, $result->getTransNo());
        $this->assertSame('PAY-001', $result->getReference());
    }

    public function testGetPaymentReturnsNullWhenNotFound(): void
    {
        $glTrans = $this->createMock(GlTransNative::class);
        $bankTrans = $this->createMock(BankTransNative::class);
        $debtorTrans = $this->createMock(DebtorTransNative::class);
        $comments = $this->createMock(CommentsNative::class);
        $reference = $this->createMock(ReferenceNative::class);
        $bankAccount = $this->createMock(BankAccountNative::class);
        $companyPrefs = $this->createMock(CompanyPrefsNative::class);
        $customer = $this->createMock(CustomerNative::class);
        $exchangeRate = $this->createMock(ExchangeRateNative::class);
        $hooks = $this->createMock(HooksNative::class);
        $transaction = $this->createMock(TransactionNative::class);
        $misc = $this->createMock(MiscNative::class);

        $db = new FakeDbAdapter([], 0);

        $svc = new CustomerPaymentService(
            new DebtorTransactionRepository($db),
            $glTrans, $bankTrans, $debtorTrans, $comments, $reference,
            $bankAccount, $companyPrefs, $customer, $exchangeRate,
            $hooks, $transaction, $misc
        );

        $result = $svc->getPayment(999);

        $this->assertNull($result);
    }
}
