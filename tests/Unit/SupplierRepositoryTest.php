<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SupplierRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SupplierRepositoryTest extends TestCase
{
    public function testFindById(): void
    {
        $db = new FakeDbAdapter([['supplier_id' => '5', 'supp_name' => 'Supplier Co', 'supp_ref' => 'SUP001', 'address' => '', 'supp_address' => '', 'gst_no' => '', 'contact' => '', 'supp_account_no' => '', 'website' => '', 'bank_account' => '', 'curr_code' => null, 'payment_terms' => null, 'tax_included' => '0', 'dimension_id' => '0', 'dimension2_id' => '0', 'tax_group_id' => null, 'credit_limit' => '0', 'purchase_account' => '', 'payable_account' => '', 'payment_discount_account' => '', 'notes' => '', 'inactive' => '0']], 1);
        $repo = new SupplierRepository($db);

        $result = $repo->findById(5);

        $this->assertNotNull($result);
        $this->assertSame(5, $result->getSupplierId());
        $this->assertSame('Supplier Co', $result->getSuppName());
        $this->assertStringContainsString('0_suppliers', $db->lastSql);
    }

    public function testFindById_returnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierRepository($db);

        $this->assertNull($repo->findById(999));
    }

    public function testFindByRef(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierRepository($db);

        $result = $repo->findByRef('SUP001');

        $this->assertNull($result);
        $this->assertStringContainsString('supp_ref = ?', $db->lastSql);
    }

    public function testFindByName(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierRepository($db);

        $results = $repo->findByName('Supplier');

        $this->assertIsArray($results);
        $this->assertStringContainsString('LIKE ?', $db->lastSql);
    }

    public function testFindActive(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new SupplierRepository($db);

        $results = $repo->findActive();

        $this->assertIsArray($results);
        $this->assertStringContainsString('inactive = ?', $db->lastSql);
    }

    public function testExists_returnsTrue(): void
    {
        $db = new FakeDbAdapter([['cnt' => '1']], 1);
        $repo = new SupplierRepository($db);

        $this->assertTrue($repo->exists(5));
    }

    public function testExists_returnsFalse(): void
    {
        $db = new FakeDbAdapter([['cnt' => '0']], 1);
        $repo = new SupplierRepository($db);

        $this->assertFalse($repo->exists(999));
    }
}
