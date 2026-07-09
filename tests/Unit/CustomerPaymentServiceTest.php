<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\CustomerPaymentRequest;
use FrontAccounting\Service\CustomerPaymentService;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerPaymentServiceTest extends TestCase
{
    private CustomerPaymentService $svc;

    protected function setUp(): void
    {
        $db = new FakeDbAdapter([], 200);
        $repo = new DebtorTransactionRepository($db);
        $this->svc = $this->getMockBuilder(CustomerPaymentService::class)
            ->setConstructorArgs([$repo])
            ->onlyMethods(['callFaWriteCustomerPayment'])
            ->getMock();
    }

    public function testCreatePaymentDelegatesToFaCore(): void
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

        $this->svc->expects($this->once())->method('callFaWriteCustomerPayment')
            ->with($this->equalTo($request))
            ->willReturn(201);

        $result = $this->svc->createPayment($request);

        $this->assertSame(201, $result);
    }

    public function testCreatePaymentReturnsInteger(): void
    {
        $request = new CustomerPaymentRequest(
            transNo: 0,
            customerId: 1,
            branchId: 1,
            bankAccount: 1,
            date: '2026-07-09',
            ref: 'PAY-002',
            amount: 250.50,
        );

        $this->svc->expects($this->once())->method('callFaWriteCustomerPayment')
            ->willReturn(202);

        $result = $this->svc->createPayment($request);

        $this->assertIsInt($result);
        $this->assertSame(202, $result);
    }

    public function testGetPaymentDelegatesToRepository(): void
    {
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

        $svc = new CustomerPaymentService(new DebtorTransactionRepository($db));

        $result = $svc->getPayment(201);

        $this->assertNotNull($result);
        $this->assertSame(201, $result->getTransNo());
        $this->assertSame('PAY-001', $result->getReference());
    }

    public function testGetPaymentReturnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $svc = new CustomerPaymentService(new DebtorTransactionRepository($db));

        $result = $svc->getPayment(999);

        $this->assertNull($result);
    }
}
