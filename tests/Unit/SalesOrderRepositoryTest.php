<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SalesOrderRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SalesOrderRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([
            ['order_no' => '100', 'trans_type' => '30', 'version' => '0', 'type' => '0', 'debtor_no' => '3', 'branch_code' => '1', 'reference' => 'SO-001', 'customer_ref' => '', 'comments' => null, 'ord_date' => '2026-01-15', 'order_type' => '1', 'ship_via' => '2', 'delivery_address' => '', 'contact_phone' => null, 'contact_email' => null, 'deliver_to' => '', 'freight_cost' => '0', 'from_stk_loc' => '', 'delivery_date' => '2026-01-15', 'payment_terms' => null, 'total' => '500', 'prep_amount' => '0', 'alloc' => '0'],
        ], 1);
        $repo = new SalesOrderRepository($db);

        $result = $repo->findById(100);

        $this->assertNotNull($result);
        $this->assertSame(100, $result->getOrderNo());
        $this->assertSame(3, $result->getDebtorNo());
        $this->assertStringContainsString('0_sales_orders', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByDebtor(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderRepository($db);

        $results = $repo->findByDebtor(3);

        $this->assertIsArray($results);
        $this->assertStringContainsString('debtor_no = ?', $db->lastSql);
        $this->assertStringContainsString('trans_type = 30', $db->lastSql);
        $this->assertStringContainsString('LIMIT 50', $db->lastSql);
    }

    public function testFindByReference(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderRepository($db);

        $results = $repo->findByReference('SO-001');

        $this->assertIsArray($results);
        $this->assertStringContainsString('reference = ?', $db->lastSql);
    }

    public function testFindOpen(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SalesOrderRepository($db);

        $results = $repo->findOpen();

        $this->assertIsArray($results);
        $this->assertStringContainsString('trans_type = 30', $db->lastSql);
        $this->assertStringContainsString('total - alloc > 0.005', $db->lastSql);
    }
}
