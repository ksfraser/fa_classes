<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\LocStock;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class LocStockRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}loc_stock WHERE stock_id = ? ORDER BY loc_code";
        $rows = $this->db->query($sql, [$stockId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByLocation(string $locCode): array
    {
        $sql = "SELECT * FROM {$this->prefix}loc_stock WHERE loc_code = ? ORDER BY stock_id";
        $rows = $this->db->query($sql, [$locCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOne(string $locCode, string $stockId): ?LocStock
    {
        $sql = "SELECT * FROM {$this->prefix}loc_stock WHERE loc_code = ? AND stock_id = ? LIMIT 1";
        $rows = $this->db->query($sql, [$locCode, $stockId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findLowStock(string $stockId, float $threshold): array
    {
        $sql = "SELECT * FROM {$this->prefix}loc_stock WHERE stock_id = ? AND qty_on_hand < ? ORDER BY loc_code";
        $rows = $this->db->query($sql, [$stockId, $threshold]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): LocStock
    {
        return new LocStock(
            (string)$row['loc_code'],
            (string)$row['stock_id'],
            (float)($row['qty_on_hand'] ?? 0),
            isset($row['expiry_date']) ? (string)$row['expiry_date'] : null
        );
    }
}
