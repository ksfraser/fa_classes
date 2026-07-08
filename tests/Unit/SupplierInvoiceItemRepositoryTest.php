<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SupplierInvoiceItemRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SupplierInvoiceItemRepositoryTest extends TestCase
{
    public function testFindByTransaction(): void
    {
        $db = new FakeDbAdapter([
            ['id' => '1', 'supp_trans_type' => '20', 'supp_trans_no' => '1001', 'stock_id' => 'ITEM01', 'description' => 'Test', 'unit_price' => '10', 'unit_tax' => '1', 'quantity' => '5', 'grn_item_id' => '10', 'po_detail_item_id' => '50', 'memo_' => '', 'dimension_id' => '0', 'dimension2_id' => '0'],
        ], 1);
        $repo = new SupplierInvoiceItemRepository($db);

        $results = $repo->findByTransaction(20, 1001);

        $this->assertCount(1, $results);
        $this->assertSame('ITEM01', $results[0]->getStockId());
        $this->assertStringContainsString('0_supp_invoice_items inv', $db->lastSql);
        $this->assertStringContainsString('LEFT JOIN 0_grn_items', $db->lastSql);
        $this->assertStringContainsString('LEFT JOIN 0_stock_master', $db->lastSql);
        $this->assertStringContainsString('LEFT JOIN 0_item_tax_types', $db->lastSql);
    }

    public function testFindByGrnItem(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierInvoiceItemRepository($db);

        $results = $repo->findByGrnItem(10);

        $this->assertIsArray($results);
        $this->assertStringContainsString('grn_item_id = ?', $db->lastSql);
    }

    public function testFindByPoDetailItem(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierInvoiceItemRepository($db);

        $results = $repo->findByPoDetailItem(50);

        $this->assertIsArray($results);
        $this->assertStringContainsString('po_detail_item_id = ?', $db->lastSql);
    }

    public function testVoid(): void
    {
        $db = new FakeDbAdapter([], 0, 3);
        $repo = new SupplierInvoiceItemRepository($db);

        $result = $repo->void(20, 1001);

        $this->assertSame(3, $result);
        $this->assertStringContainsString('SET quantity = 0, unit_price = 0', $db->lastSql);
    }
}
