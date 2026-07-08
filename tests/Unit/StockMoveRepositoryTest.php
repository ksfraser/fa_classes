<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\StockMoveRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class StockMoveRepositoryTest extends TestCase
{
    public function testFindByTypeAndNo(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMoveRepository($db);

        $results = $repo->findByTypeAndNo(20, 1001);

        $this->assertIsArray($results);
        $this->assertStringContainsString('0_stock_moves', $db->lastSql);
        $this->assertStringContainsString('type = ? AND trans_no = ?', $db->lastSql);
    }

    public function testFindByStockId(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMoveRepository($db);

        $results = $repo->findByStockId('ITEM01');

        $this->assertIsArray($results);
        $this->assertStringContainsString('stock_id = ?', $db->lastSql);
        $this->assertStringContainsString('LIMIT 100', $db->lastSql);
    }

    public function testFindByLocation(): void
    {
        $db = new FakeDbAdapter([], 0);
        $repo = new StockMoveRepository($db);

        $results = $repo->findByLocation('LOC');

        $this->assertIsArray($results);
        $this->assertStringContainsString('loc_code = ?', $db->lastSql);
    }

    public function testUpdateStandardCost(): void
    {
        $db = new FakeDbAdapter([], 0, 3);
        $repo = new StockMoveRepository($db);

        $result = $repo->updateStandardCost(20, 1001, 45.0);

        $this->assertSame(3, $result);
        $this->assertStringContainsString('SET standard_cost = ?', $db->lastSql);
        $this->assertStringContainsString('type = ? AND trans_no = ?', $db->lastSql);
    }
}
