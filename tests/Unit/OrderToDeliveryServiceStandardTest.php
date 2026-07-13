<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Service\Standard\OrderToDeliveryServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class OrderToDeliveryServiceStandardTest extends TestCase
{
    public function testGetItemDelaysReturnsAllRowsWhenNoFilter(): void
    {
        $db = new FakeDbAdapter([
            ['stock_id' => 'ITEM1', 'supplier' => 'Supplier A', 'days' => '7'],
            ['stock_id' => 'ITEM2', 'supplier' => 'Supplier B', 'days' => '14'],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getItemDelays();

        $this->assertCount(2, $result);
        $this->assertSame('ITEM1', $result[0]['stock_id']);
        $this->assertSame('Supplier A', $result[0]['supplier']);
        $this->assertSame(7, $result[0]['days']);
        $this->assertSame('ITEM2', $result[1]['stock_id']);
        $this->assertSame(14, $result[1]['days']);
    }

    public function testGetItemDelaysFiltersByItemCode(): void
    {
        $db = new FakeDbAdapter([
            ['stock_id' => 'ITEM1', 'supplier' => 'Supplier A', 'days' => '7'],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getItemDelays('ITEM1');

        $this->assertCount(1, $result);
        $this->assertSame('ITEM1', $result[0]['stock_id']);
        $this->assertSame('Supplier A', $result[0]['supplier']);
        $this->assertSame(7, $result[0]['days']);
        $this->assertStringContainsString('d.item_code = ?', $db->lastSql);
        $this->assertSame(['ITEM1'], $db->lastParams);
    }

    public function testGetItemDelaysReturnsEmptyWhenNoData(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $this->assertSame([], $svc->getItemDelays());
    }

    public function testGetSupplierDelaysReturnsAllRowsWhenNoFilter(): void
    {
        $db = new FakeDbAdapter([
            ['order_number' => '101', 'supplier' => 'Supplier A', 'days' => '5'],
            ['order_number' => '102', 'supplier' => 'Supplier B', 'days' => '10'],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getSupplierDelays();

        $this->assertCount(2, $result);
        $this->assertSame(101, $result[0]['order_number']);
        $this->assertSame('Supplier A', $result[0]['supplier']);
        $this->assertSame(5, $result[0]['days']);
    }

    public function testGetSupplierDelaysFiltersBySupplierName(): void
    {
        $db = new FakeDbAdapter([
            ['order_number' => '101', 'supplier' => 'Supplier A', 'days' => '5'],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getSupplierDelays('Supplier A');

        $this->assertCount(1, $result);
        $this->assertSame(101, $result[0]['order_number']);
        $this->assertSame('Supplier A', $result[0]['supplier']);
        $this->assertStringContainsString('s.supp_name = ?', $db->lastSql);
        $this->assertSame(['Supplier A'], $db->lastParams);
    }

    public function testGetSupplierDelaysReturnsEmptyWhenNoData(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $this->assertSame([], $svc->getSupplierDelays());
    }

    public function testGetOrderDeliveryDetailsReturnsAllRowsWhenNoFilter(): void
    {
        $db = new FakeDbAdapter([
            [
                'order_number' => '101',
                'supplier' => 'Supplier A',
                'days' => '5',
                'order_date' => '2026-01-01',
                'delivery_date' => '2026-01-06',
                'stock_id' => 'ITEM1',
                'quantity_ordered' => '100.0',
                'quantity_received' => '80.0',
            ],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getOrderDeliveryDetails();

        $this->assertCount(1, $result);
        $this->assertSame(101, $result[0]['order_number']);
        $this->assertSame('Supplier A', $result[0]['supplier']);
        $this->assertSame(5, $result[0]['days']);
        $this->assertSame('2026-01-01', $result[0]['order_date']);
        $this->assertSame('2026-01-06', $result[0]['delivery_date']);
        $this->assertSame('ITEM1', $result[0]['stock_id']);
        $this->assertSame(100.0, $result[0]['quantity_ordered']);
        $this->assertSame(80.0, $result[0]['quantity_received']);
    }

    public function testGetOrderDeliveryDetailsFiltersByOrderNo(): void
    {
        $db = new FakeDbAdapter([
            [
                'order_number' => '101',
                'supplier' => 'Supplier A',
                'days' => '5',
                'order_date' => '2026-01-01',
                'delivery_date' => '2026-01-06',
                'stock_id' => 'ITEM1',
                'quantity_ordered' => '100.0',
                'quantity_received' => '80.0',
            ],
        ]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $result = $svc->getOrderDeliveryDetails(101);

        $this->assertCount(1, $result);
        $this->assertStringContainsString('d.order_no = ?', $db->lastSql);
        $this->assertSame([101], $db->lastParams);
    }

    public function testGetOrderDeliveryDetailsReturnsEmptyWhenNoData(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new OrderToDeliveryServiceStandard($db);

        $this->assertSame([], $svc->getOrderDeliveryDetails());
    }
}
