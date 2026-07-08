<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DebtorTransactionRepositoryTest extends TestCase
{
    public function testFindByTypeAndNo(): void
    {
        $db = new FakeDbAdapter([
            ['trans_no' => '2001', 'type' => '10', 'debtor_no' => '3', 'branch_code' => '1', 'tran_date' => '2026-01-15', 'due_date' => '2026-02-14', 'reference' => 'INV-001', 'order_' => '500', 'ov_amount' => '1500', 'ov_gst' => '150', 'ov_freight' => '50', 'ov_freight_tax' => '5', 'ov_discount' => '30', 'alloc' => '1200', 'prep_amount' => '0', 'rate' => '1', 'ship_via' => '2', 'dimension_id' => '0', 'dimension2_id' => '0', 'payment_terms' => '1', 'tax_included' => '0'],
        ], 1);
        $repo = new DebtorTransactionRepository($db);

        $result = $repo->findByTypeAndNo(10, 2001);

        $this->assertNotNull($result);
        $this->assertSame(2001, $result->getTransNo());
        $this->assertSame(3, $result->getDebtorNo());
        $this->assertStringContainsString('0_debtor_trans', $db->lastSql);
        $this->assertStringContainsString('type = ? AND trans_no = ?', $db->lastSql);
    }

    public function testFindByTypeAndNo_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorTransactionRepository($db);

        $result = $repo->findByTypeAndNo(10, 999);

        $this->assertNull($result);
    }

    public function testFindByCustomer(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorTransactionRepository($db);

        $results = $repo->findByCustomer(3);

        $this->assertIsArray($results);
        $this->assertStringContainsString('0_debtor_trans', $db->lastSql);
        $this->assertStringContainsString('debtor_no = ?', $db->lastSql);
        $this->assertStringContainsString('ov_amount != 0', $db->lastSql);
        $this->assertStringContainsString('type != 13', $db->lastSql);
    }

    public function testFindByOrder(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorTransactionRepository($db);

        $results = $repo->findByOrder(500);

        $this->assertIsArray($results);
        $this->assertStringContainsString('order_ = ?', $db->lastSql);
    }

    public function testGetUnallocatedByCustomer(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorTransactionRepository($db);

        $results = $repo->getUnallocatedByCustomer(3);

        $this->assertIsArray($results);
        $this->assertStringContainsString('ABS(ov_amount + ov_gst + ov_freight + ov_freight_tax + ov_discount - alloc)', $db->lastSql);
    }

    public function testVoid(): void
    {
        $db = new FakeDbAdapter([], 0, 1);
        $repo = new DebtorTransactionRepository($db);

        $result = $repo->void(10, 2001);

        $this->assertSame(1, $result);
        $this->assertStringContainsString('version = version + 1', $db->lastSql);
    }

    public function testClear(): void
    {
        $db = new FakeDbAdapter([], 0, 1);
        $repo = new DebtorTransactionRepository($db);

        $result = $repo->clear(10, 2001);

        $this->assertSame(1, $result);
        $this->assertStringContainsString('DELETE', $db->lastSql);
    }
}
