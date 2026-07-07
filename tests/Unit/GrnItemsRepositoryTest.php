<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\GrnItemsRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class GrnItemsRepositoryTest extends TestCase
{
    public function testRecalcQtyInv_updatesGrnItems(): void
    {
        $db = new FakeDbAdapter([], 1, 5);
        $repo = new GrnItemsRepository($db);

        $result = $repo->recalcQtyInv();

        $sql = $db->lastSql;
        $this->assertNotNull($sql);
        $this->assertStringContainsString('UPDATE', $sql);
        $this->assertStringContainsString('0_grn_items', $sql);
        $this->assertStringContainsString('quantity_inv', $sql);
        $this->assertStringContainsString('0_supp_invoice_items', $sql);
        $this->assertStringContainsString('si.grn_item_id', $sql);
        $this->assertStringContainsString('si.supp_trans_type = 20', $sql);
        $this->assertStringContainsString('st.ov_amount != 0', $sql);
        $this->assertStringContainsString('COALESCE', $sql);
        $this->assertStringContainsString('0.005', $sql);
        $this->assertSame(5, $result);
    }
}
