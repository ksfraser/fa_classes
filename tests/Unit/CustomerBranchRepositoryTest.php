<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerBranchRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['branch_code' => '1', 'debtor_no' => '3', 'br_name' => 'Main', 'branch_ref' => 'BR-001', 'br_address' => '', 'area' => null, 'salesman' => '0', 'default_location' => '', 'tax_group_id' => null, 'sales_account' => '', 'sales_discount_account' => '', 'receivables_account' => '', 'payment_discount_account' => '', 'default_ship_via' => '1', 'br_post_address' => '', 'group_no' => '0', 'notes' => '', 'bank_account' => null, 'inactive' => '0']], 1);
        $repo = new CustomerBranchRepository($db);

        $result = $repo->findById(1, 3);

        $this->assertNotNull($result);
        $this->assertSame(1, $result->getBranchCode());
        $this->assertSame(3, $result->getDebtorNo());
        $this->assertStringContainsString('0_cust_branch', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CustomerBranchRepository($db);

        $this->assertNull($repo->findById(999, 3));
    }

    public function testFindByDebtor(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CustomerBranchRepository($db);

        $results = $repo->findByDebtor(3);

        $this->assertIsArray($results);
        $this->assertStringContainsString('debtor_no = ?', $db->lastSql);
    }

    public function testFindByBranchRef(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CustomerBranchRepository($db);

        $results = $repo->findByBranchRef('BR-001');

        $this->assertIsArray($results);
        $this->assertStringContainsString('branch_ref = ?', $db->lastSql);
    }

    public function testFindByArea(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CustomerBranchRepository($db);

        $results = $repo->findByArea(2);

        $this->assertIsArray($results);
        $this->assertStringContainsString('area = ?', $db->lastSql);
    }

    public function testFindBySalesman(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new CustomerBranchRepository($db);

        $results = $repo->findBySalesman(5);

        $this->assertIsArray($results);
        $this->assertStringContainsString('salesman = ?', $db->lastSql);
    }
}
