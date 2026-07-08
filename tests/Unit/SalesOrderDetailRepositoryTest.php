<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SalesOrderDetailRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SalesOrderDetailRepositoryTest extends TestCase
{
    public function testFindByOrder(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderDetailRepository($db);

        $results = $repo->findByOrder(100);

        $this->assertIsArray($results);
        $this->assertStringContainsString('0_sales_order_details', $db->lastSql);
        $this->assertStringContainsString('order_no = ? AND trans_type = ?', $db->lastSql);
    }

    public function testFindByStkCode(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderDetailRepository($db);

        $results = $repo->findByStkCode('ITEM01');

        $this->assertIsArray($results);
        $this->assertStringContainsString('JOIN 0_sales_orders', $db->lastSql);
        $this->assertStringContainsString('sod.stk_code = ?', $db->lastSql);
        $this->assertStringContainsString('sod.quantity - sod.qty_sent > 0.005', $db->lastSql);
    }

    public function testFindOpenByStkCode(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderDetailRepository($db);

        $results = $repo->findOpenByStkCode('ITEM01');

        $this->assertIsArray($results);
        $this->assertStringContainsString('JOIN 0_sales_orders', $db->lastSql);
        $this->assertStringContainsString('so.total - so.alloc > 0.005', $db->lastSql);
        $this->assertStringContainsString('sod.quantity - sod.qty_sent > 0.005', $db->lastSql);
    }
}
