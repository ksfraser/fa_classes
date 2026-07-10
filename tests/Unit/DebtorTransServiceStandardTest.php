<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\Standard\DebtorTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DebtorTransServiceStandardTest extends TestCase
{
    public function testWriteCustomerTransWithExistingTransNo(): void
    {
        $db = new FakeDbAdapter([['next_no' => 1]], 0, 1);
        $repo = new DebtorTransactionRepository($db);
        $svc = new DebtorTransServiceStandard($repo);

        $result = $svc->writeCustomerTrans(12, 201, 1, 1, '2026-07-10', 'PAY-001', 100.00);

        $this->assertSame(201, $result);
        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
    }

    public function testWriteCustomerTransGeneratesTransNoWhenZero(): void
    {
        $db = new FakeDbAdapter([
            ['next_no' => 5],
            ['next_no' => 5],
        ], 0, 1);
        $repo = new DebtorTransactionRepository($db);
        $svc = new DebtorTransServiceStandard($repo);

        $result = $svc->writeCustomerTrans(12, 0, 1, 1, '2026-07-10', 'PAY-001', 100.00);

        $this->assertSame(5, $result);
    }

    public function testGetCustomerTransReturnsRow(): void
    {
        $db = new FakeDbAdapter([[
            'trans_no' => 201,
            'type' => 12,
            'debtor_no' => 1,
            'branch_code' => 1,
            'tran_date' => '2026-07-10',
            'due_date' => '2026-07-10',
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
        $repo = new DebtorTransactionRepository($db);
        $svc = new DebtorTransServiceStandard($repo);

        $result = $svc->getCustomerTrans(201, 12);

        $this->assertIsArray($result);
        $this->assertSame(201, (int)$result['trans_no']);
        $this->assertSame(100.00, (float)$result['ov_amount']);
    }

    public function testGetCustomerTransReturnsEmptyWhenNotFound(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new DebtorTransactionRepository($db);
        $svc = new DebtorTransServiceStandard($repo);

        $this->assertSame([], $svc->getCustomerTrans(999, 12));
    }
}
