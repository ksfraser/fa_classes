<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\PurchaseOrderDetailRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class PurchaseOrderDetailRepositoryTest extends TestCase
{
    public function testFindByOrder(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderDetailRepository($db);

        $results = $repo->findByOrder(500);

        $this->assertIsArray($results);
        $this->assertStringContainsString('0_purch_order_details', $db->lastSql);
        $this->assertStringContainsString('order_no = ?', $db->lastSql);
    }

    public function testFindByItemCode(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderDetailRepository($db);

        $results = $repo->findByItemCode('ITEM01');

        $this->assertIsArray($results);
        $this->assertStringContainsString('item_code = ?', $db->lastSql);
        $this->assertStringContainsString('quantity_ordered - quantity_received > 0.005', $db->lastSql);
    }

    public function testFindOpenByItem(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new PurchaseOrderDetailRepository($db);

        $results = $repo->findOpenByItem('ITEM01');

        $this->assertIsArray($results);
        $this->assertStringContainsString('JOIN 0_purch_orders', $db->lastSql);
        $this->assertStringContainsString('pod.item_code = ?', $db->lastSql);
        $this->assertStringContainsString('po.total - po.alloc > 0.005', $db->lastSql);
    }
}
