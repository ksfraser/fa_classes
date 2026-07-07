<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\PurchOrderDetailsRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class PurchOrderDetailsRepositoryTest extends TestCase
{
    public function testRecalcQtyInvoiced_updatesPurchOrderDetails(): void
    {
        $db = new FakeDbAdapter([], 1, 4);
        $repo = new PurchOrderDetailsRepository($db);

        $result = $repo->recalcQtyInvoiced();

        $sql = $db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_purch_order_details', $sql);
        $this->assertStringContainsString('pod.qty_invoiced', $sql);
        $this->assertStringContainsString('0_supp_invoice_items', $sql);
        $this->assertStringContainsString('si.po_detail_item_id', $sql);
        $this->assertStringContainsString('SUM(si.quantity)', $sql);
        $this->assertStringContainsString('si.supp_trans_type = 20', $sql);
        $this->assertStringContainsString('st.ov_amount != 0', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(4, $result);
    }

    public function testRecalcQtyReceived_updatesPurchOrderDetails(): void
    {
        $db = new FakeDbAdapter([], 1, 2);
        $repo = new PurchOrderDetailsRepository($db);

        $result = $repo->recalcQtyReceived();

        $sql = $db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_purch_order_details', $sql);
        $this->assertStringContainsString('pod.quantity_received', $sql);
        $this->assertStringContainsString('0_grn_items', $sql);
        $this->assertStringContainsString('po_detail_item', $sql);
        $this->assertStringContainsString('SUM(qty_recd)', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(2, $result);
    }
}
