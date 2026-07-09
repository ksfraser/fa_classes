<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\StockMasterRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class StockMasterRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([
            ['stock_id' => 'ITEM01', 'category_id' => '1', 'tax_type_id' => '2', 'description' => 'Test', 'long_description' => '', 'units' => 'each', 'mb_flag' => 'B', 'sales_account' => '', 'cogs_account' => '', 'inventory_account' => '', 'adjustment_account' => '', 'wip_account' => '', 'dimension_id' => null, 'dimension2_id' => null, 'purchase_cost' => '10', 'material_cost' => '5', 'labour_cost' => '3', 'overhead_cost' => '2', 'inactive' => '0', 'no_sale' => '0', 'no_purchase' => '0', 'editable' => '1'],
        ], 1);
        $repo = new StockMasterRepository($db);

        $result = $repo->findById('ITEM01');

        $this->assertNotNull($result);
        $this->assertSame('ITEM01', $result->getStockId());
        $this->assertStringContainsString('stock_id = ?', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMasterRepository($db);

        $this->assertNull($repo->findById('NONEXISTENT'));
    }

    public function testFindByCategory(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMasterRepository($db);

        $results = $repo->findByCategory(1);

        $this->assertIsArray($results);
        $this->assertStringContainsString('category_id = ?', $db->lastSql);
        $this->assertStringContainsString('inactive = 0', $db->lastSql);
    }

    public function testFindActive(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMasterRepository($db);

        $results = $repo->findActive();

        $this->assertIsArray($results);
        $this->assertStringContainsString('inactive = ?', $db->lastSql);
    }

    public function testSearch(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMasterRepository($db);

        $results = $repo->search('ITEM');

        $this->assertIsArray($results);
        $this->assertStringContainsString('LIKE ?', $db->lastSql);
        $this->assertStringContainsString('LIMIT 50', $db->lastSql);
    }

    public function testExists_returnsTrue(): void
    {
        $db = new FakeDbAdapter([['cnt' => '1']], 1);
        $repo = new StockMasterRepository($db);

        $this->assertTrue($repo->exists('ITEM01'));
    }

    public function testExists_returnsFalse(): void
    {
        $db = new FakeDbAdapter([['cnt' => '0']], 1);
        $repo = new StockMasterRepository($db);

        $this->assertFalse($repo->exists('NONEXISTENT'));
    }
}
