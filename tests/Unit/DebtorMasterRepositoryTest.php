<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\DebtorMasterRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DebtorMasterRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['debtor_no' => '3', 'name' => 'Acme', 'debtor_ref' => 'ACME001', 'address' => null, 'tax_id' => '', 'curr_code' => 'USD', 'sales_type' => '1', 'dimension_id' => '0', 'dimension2_id' => '0', 'credit_status' => '0', 'payment_terms' => null, 'discount' => '0', 'pymt_discount' => '0', 'credit_limit' => '1000', 'notes' => '', 'inactive' => '0']], 1);
        $repo = new DebtorMasterRepository($db);

        $result = $repo->findById(3);

        $this->assertNotNull($result);
        $this->assertSame(3, $result->getDebtorNo());
        $this->assertSame('Acme', $result->getName());
        $this->assertStringContainsString('0_debtors_master', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorMasterRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByRef(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorMasterRepository($db);

        $result = $repo->findByRef('ACME001');

        $this->assertNull($result);
        $this->assertStringContainsString('debtor_ref = ?', $db->lastSql);
    }

    public function testFindByName(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorMasterRepository($db);

        $results = $repo->findByName('Acme');

        $this->assertIsArray($results);
        $this->assertStringContainsString('LIKE ?', $db->lastSql);
    }

    public function testFindActive(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorMasterRepository($db);

        $results = $repo->findActive();

        $this->assertIsArray($results);
        $this->assertStringContainsString('inactive = ?', $db->lastSql);
    }

    public function testExists_returnsTrue(): void
    {
        $db = new FakeDbAdapter([['cnt' => '1']], 1);
        $repo = new DebtorMasterRepository($db);

        $this->assertTrue($repo->exists(3));
    }

    public function testExists_returnsFalse(): void
    {
        $db = new FakeDbAdapter([['cnt' => '0']], 1);
        $repo = new DebtorMasterRepository($db);

        $this->assertFalse($repo->exists(999));
    }
}
