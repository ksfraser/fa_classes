<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\SalesOrderDetailsRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class SalesOrderDetailsRepositoryTest extends TestCase
{
    public function testRecalcQtySent_updatesSalesOrderDetails(): void
    {
        $db = new FakeDbAdapter([], 1, 7);
        $repo = new SalesOrderDetailsRepository($db);

        $result = $repo->recalcQtySent();

        $sql = $db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_sales_order_details', $sql);
        $this->assertStringContainsString('sod.qty_sent', $sql);
        $this->assertStringContainsString('0_debtor_trans_details', $sql);
        $this->assertStringContainsString('dtd.src_id', $sql);
        $this->assertStringContainsString('SUM(dtd.qty_done)', $sql);
        $this->assertStringContainsString('dtd.debtor_trans_type = 13', $sql);
        $this->assertStringContainsString('dt.ov_amount != 0', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(7, $result);
    }

    public function testRecalcInvoiced_updatesSalesOrderDetails(): void
    {
        $db = new FakeDbAdapter([], 1, 3);
        $repo = new SalesOrderDetailsRepository($db);

        $result = $repo->recalcInvoiced();

        $sql = $db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_sales_order_details', $sql);
        $this->assertStringContainsString('sod.invoiced', $sql);
        $this->assertStringContainsString('0_debtor_trans_details', $sql);
        $this->assertStringContainsString('SUM(dtd.quantity)', $sql);
        $this->assertStringContainsString('dtd.debtor_trans_type = 10', $sql);
        $this->assertStringContainsString('dt.ov_amount != 0', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(3, $result);
    }
}
