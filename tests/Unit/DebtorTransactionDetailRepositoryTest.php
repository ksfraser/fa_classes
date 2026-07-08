<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\DebtorTransactionDetailRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DebtorTransactionDetailRepositoryTest extends TestCase
{
    public function testFindByTransaction(): void
    {
        $db = new FakeDbAdapter([
            ['id' => '1', 'debtor_trans_no' => '2001', 'debtor_trans_type' => '10', 'stock_id' => 'ITEM01', 'description' => 'Test', 'unit_price' => '15', 'unit_tax' => '1.5', 'quantity' => '3', 'discount_percent' => '5', 'standard_cost' => '10', 'qty_done' => '2', 'src_id' => '100'],
        ], 1);
        $repo = new DebtorTransactionDetailRepository($db);

        $results = $repo->findByTransaction(10, 2001);

        $this->assertCount(1, $results);
        $this->assertSame('ITEM01', $results[0]->getStockId());
        $this->assertStringContainsString('0_debtor_trans_details line', $db->lastSql);
        $this->assertStringContainsString('LEFT JOIN 0_stock_master', $db->lastSql);
    }

    public function testFindBySrcId(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new DebtorTransactionDetailRepository($db);

        $results = $repo->findBySrcId(100);

        $this->assertIsArray($results);
        $this->assertStringContainsString('src_id = ?', $db->lastSql);
    }

    public function testVoid(): void
    {
        $db = new FakeDbAdapter([], 0, 2);
        $repo = new DebtorTransactionDetailRepository($db);

        $result = $repo->void(10, 2001);

        $this->assertSame(2, $result);
        $this->assertStringContainsString('SET quantity = 0, unit_price = 0, unit_tax = 0', $db->lastSql);
        $this->assertStringContainsString('discount_percent = 0', $db->lastSql);
        $this->assertStringContainsString('standard_cost = 0', $db->lastSql);
        $this->assertStringContainsString('src_id = 0', $db->lastSql);
    }
}
