<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\PurchaseOrderRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class PurchaseOrderRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([
            ['order_no' => '500', 'supplier_id' => '5', 'comments' => null, 'ord_date' => '2026-01-15', 'reference' => 'PO-001', 'requisition_no' => null, 'into_stock_location' => 'LOC', 'delivery_address' => 'Addr', 'total' => '1000', 'prep_amount' => '100', 'alloc' => '800', 'tax_included' => '0'],
        ], 1);
        $repo = new PurchaseOrderRepository($db);

        $result = $repo->findById(500);

        $this->assertNotNull($result);
        $this->assertSame(500, $result->getOrderNo());
        $this->assertSame(5, $result->getSupplierId());
        $this->assertStringContainsString('0_purch_orders', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindBySupplier(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderRepository($db);

        $results = $repo->findBySupplier(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('supplier_id = ?', $db->lastSql);
    }

    public function testFindByReference(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderRepository($db);

        $results = $repo->findByReference('PO-001');

        $this->assertIsArray($results);
        $this->assertStringContainsString('reference = ?', $db->lastSql);
    }

    public function testFindOpen(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderRepository($db);

        $results = $repo->findOpen();

        $this->assertIsArray($results);
        $this->assertStringContainsString('po.total - po.alloc > 0.005', $db->lastSql);
    }
}
