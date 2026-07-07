<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SupplierAllocation;
use FrontAccounting\Repository\AllocationRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class AllocationRepositoryTest extends TestCase
{
    /** @var FakeDbAdapter */
    private $db;
    /** @var AllocationRepository */
    private $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new FakeDbAdapter([], 1, 3);
        $this->repo = new AllocationRepository($this->db);
    }

    // ================================================================
    // createSupplierAllocation
    // ================================================================

    public function testCreateSupplierAllocation_buildsInsertSql(): void
    {
        $dto = new SupplierAllocation(
            150.00,
            22, 101, 20, 55, 7, '2025-06-01'
        );

        $this->repo->createSupplierAllocation($dto);

        $sql = $this->db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('INSERT', $sql);
        $this->assertStringContainsString('0_supp_allocations', $sql);
        $this->assertStringContainsString('amt', $sql);
        $this->assertStringContainsString('date_alloc', $sql);
        $this->assertStringContainsString('trans_type_from', $sql);
        $this->assertStringContainsString('trans_no_from', $sql);
        $this->assertStringContainsString('trans_no_to', $sql);
        $this->assertStringContainsString('trans_type_to', $sql);
        $this->assertStringContainsString('person_id', $sql);

        $this->assertSame([150.0, '2025-06-01', 22, 101, 55, 20, 7], $this->db->lastParams);
    }

    public function testCreateSupplierAllocation_withDifferentValues(): void
    {
        $dto = new SupplierAllocation(
            1234.56, 22, 200, 20, 99, 3, '2025-07-15'
        );

        $this->repo->createSupplierAllocation($dto);

        $this->assertSame([1234.56, '2025-07-15', 22, 200, 99, 20, 3], $this->db->lastParams);
    }

    // ================================================================
    // updateSupplierTransactionAllocation
    // ================================================================

    public function testUpdateSupplierTransactionAllocation_updatesSuppTrans(): void
    {
        $this->repo->updateSupplierTransactionAllocation(22, 101, 7);

        $sql = $this->db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_supp_trans', $sql);
        $this->assertStringContainsString('trans.alloc', $sql);
        $this->assertStringContainsString('IFNULL', $sql);
        $this->assertSame(7, $this->db->lastParams[2]);
    }

    public function testUpdateSupplierTransactionAllocation_forInvoice(): void
    {
        $this->repo->updateSupplierTransactionAllocation(20, 55, 7);

        $sql = $this->db->lastSql;
        $this->assertStringContainsString('0_supp_trans', $sql);
        $this->assertSame([20, 55, 7, 20, 55, 20, 55, 7], $this->db->lastParams);
    }

    public function testUpdateSupplierTransactionAllocation_usesPurchOrdersForType18(): void
    {
        $this->repo->updateSupplierTransactionAllocation(18, 42, 3);

        $sql = $this->db->lastSql;
        $this->assertStringContainsString('0_purch_orders', $sql);
        $this->assertStringNotContainsString('supp_trans', $sql);
        $this->assertStringContainsString('trans.order_no', $sql);
        $this->assertStringNotContainsString('trans.type', $sql);
    }

    // ================================================================
    // recalcSupplierAlloc
    // ================================================================

    public function testRecalcSupplierAlloc_updatesSuppTrans(): void
    {
        $result = $this->repo->recalcSupplierAlloc();

        $sql = $this->db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_supp_trans', $sql);
        $this->assertStringContainsString('st.alloc', $sql);
        $this->assertStringContainsString('0_supp_allocations', $sql);
        $this->assertStringContainsString('SUM(amt)', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(3, $result);
    }

    // ================================================================
    // recalcCustomerAlloc
    // ================================================================

    public function testRecalcCustomerAlloc_updatesDebtorTrans(): void
    {
        $result = $this->repo->recalcCustomerAlloc();

        $sql = $this->db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_debtor_trans', $sql);
        $this->assertStringContainsString('dt.alloc', $sql);
        $this->assertStringContainsString('0_cust_allocations', $sql);
        $this->assertStringContainsString('SUM(amt)', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertSame(3, $result);
    }
}
