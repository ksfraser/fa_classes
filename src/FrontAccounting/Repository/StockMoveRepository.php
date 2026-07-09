<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\StockMove;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class StockMoveRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'stock_moves';
    public function findByTypeAndNo(int $type, int $transNo): array
    {
        return $this->find(['type' => $type, 'trans_no' => $transNo], ['trans_id' => 'ASC']);
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_moves
                WHERE stock_id = ? ORDER BY tran_date DESC
                LIMIT 100";
        $rows = $this->db->query($sql, [$stockId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByLocation(string $locCode): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_moves
                WHERE loc_code = ? ORDER BY tran_date DESC
                LIMIT 100";
        $rows = $this->db->query($sql, [$locCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function updateStandardCost(int $type, int $transNo, float $standardCost): int
    {
        $sql = "UPDATE {$this->prefix}stock_moves
                SET standard_cost = ?
                WHERE type = ? AND trans_no = ?";
        return $this->db->execute($sql, [$standardCost, $type, $transNo]);
    }

    protected function hydrate(array $row): StockMove
    {
        return new StockMove(
            (int)$row['trans_id'],
            (int)$row['trans_no'],
            (string)$row['stock_id'],
            (int)$row['type'],
            (string)$row['loc_code'],
            (string)$row['tran_date'],
            (float)$row['price'],
            (string)$row['reference'],
            (float)$row['qty'],
            (float)$row['standard_cost']
        );
    }

}
