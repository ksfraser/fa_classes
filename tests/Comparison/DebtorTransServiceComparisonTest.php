<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\Native\DebtorTransServiceNative;
use FrontAccounting\Service\Standard\DebtorTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DebtorTransServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testWriteCustomerTransBothReturnInt(): void
    {
        // famock: write_customer_trans(...) returns 100, 101, ...
        $native = new DebtorTransServiceNative();

        // Standard with transNo=0: gets next trans_no (MAX+1 = 1 from fake), returns it
        $db = new FakeDbAdapter([['next_no' => 1]], 0, 1);
        $standard = new DebtorTransServiceStandard(new DebtorTransactionRepository($db));

        $nativeResult = $native->writeCustomerTrans(12, 0, 1, 1, '2026-07-10', 'PAY-001', 100.00);
        $standardResult = $standard->writeCustomerTrans(12, 0, 1, 1, '2026-07-10', 'PAY-001', 100.00);

        // Both return int (exact values differ due to mocks vs real logic)
        $this->assertIsInt($nativeResult);
        $this->assertIsInt($standardResult);
    }

    public function testGetCustomerTransBothReturnArray(): void
    {
        // famock: get_customer_trans(201, 12) returns predefined array
        $native = new DebtorTransServiceNative();

        $db = new FakeDbAdapter([[
            'trans_no' => 201,
            'type' => 12,
            'debtor_no' => 1,
            'branch_code' => 1,
            'tran_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'reference' => 'PAY-001',
            'order_' => 0,
            'ov_amount' => 100.00,
            'ov_gst' => 0.0,
            'ov_freight' => 0.0,
            'ov_freight_tax' => 0.0,
            'ov_discount' => 0.0,
            'alloc' => 0.0,
            'prep_amount' => 0.0,
            'rate' => 1.0,
            'ship_via' => 0,
            'dimension_id' => 0,
            'dimension2_id' => 0,
            'payment_terms' => 0,
            'tax_included' => 0,
        ]]);
        $standard = new DebtorTransServiceStandard(new DebtorTransactionRepository($db));

        $nativeResult = $native->getCustomerTrans(201, 12);
        $standardResult = $standard->getCustomerTrans(201, 12);

        $this->assertIsArray($nativeResult);
        $this->assertIsArray($standardResult);
        $this->assertArrayHasKey('reference', $nativeResult);
        $this->assertArrayHasKey('reference', $standardResult);
    }
}
