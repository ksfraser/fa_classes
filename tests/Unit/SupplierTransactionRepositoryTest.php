<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SupplierTransactionRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SupplierTransactionRepositoryTest extends TestCase
{
    public function testFindByTypeAndNo(): void
    {
        $db = new FakeDbAdapter([
            ['trans_no' => '1001', 'type' => '20', 'supplier_id' => '5', 'reference' => 'R1', 'supp_reference' => 'SR1', 'tran_date' => '2026-01-15', 'due_date' => '2026-02-14', 'ov_amount' => '1000', 'ov_discount' => '50', 'ov_gst' => '100', 'rate' => '1', 'alloc' => '800', 'tax_included' => '0'],
        ], 1);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->findByTypeAndNo(20, 1001);

        $this->assertNotNull($result);
        $this->assertSame(1001, $result->getTransNo());
        $this->assertStringContainsString('0_supp_trans', $db->lastSql);
        $this->assertStringContainsString('type = ? AND trans_no = ?', $db->lastSql);
    }

    public function testFindByTypeAndNo_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->findByTypeAndNo(20, 999);

        $this->assertNull($result);
    }

    public function testFindBySupplier(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierTransactionRepository($db);

        $results = $repo->findBySupplier(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('0_supp_trans', $db->lastSql);
        $this->assertStringContainsString('supplier_id = ?', $db->lastSql);
        $this->assertStringContainsString('ov_amount != 0', $db->lastSql);
        $this->assertStringContainsString('ORDER BY tran_date', $db->lastSql);
    }

    public function testIsReferenceAlreadyUsed_returnsTrue(): void
    {
        $db = new FakeDbAdapter([['cnt' => '1']], 1);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->isReferenceAlreadyUsed(5, 'SR001');

        $this->assertTrue($result);
        $this->assertStringContainsString('COUNT(*)', $db->lastSql);
    }

    public function testIsReferenceAlreadyUsed_returnsFalse(): void
    {
        $db = new FakeDbAdapter([['cnt' => '0']], 1);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->isReferenceAlreadyUsed(5, 'SR001');

        $this->assertFalse($result);
    }

    public function testGetUnallocatedBySupplier(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierTransactionRepository($db);

        $results = $repo->getUnallocatedBySupplier(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('ABS(ov_amount + ov_gst + ov_discount - alloc)', $db->lastSql);
    }

    public function testVoid(): void
    {
        $db = new FakeDbAdapter([], 0, 2);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->void(20, 1001);

        $this->assertSame(2, $result);
        $this->assertStringContainsString('SET ov_amount = 0', $db->lastSql);
    }

    public function testClear(): void
    {
        $db = new FakeDbAdapter([], 0, 1);
        $repo = new SupplierTransactionRepository($db);

        $result = $repo->clear(20, 1001);

        $this->assertSame(1, $result);
        $this->assertStringContainsString('DELETE', $db->lastSql);
    }
}
