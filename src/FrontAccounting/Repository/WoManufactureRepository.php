<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoManufacture;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoManufactureRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?WoManufacture
    {
        $sql = "SELECT * FROM {$this->prefix}wo_manufacture WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_manufacture WHERE workorder_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$workOrderId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_manufacture WHERE stock_id = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$stockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WoManufacture
    {
        return new WoManufacture(
            (int)$row['id'],
            (int)$row['workorder_id'],
            (string)$row['reference'],
            (string)$row['stock_id'],
            (float)$row['qty_manufactured'],
            (float)($row['qty_waste'] ?? 0.0),
            (string)($row['date_'] ?? ''),
            isset($row['memo_']) ? (string)$row['memo_'] : null,
            isset($row['user_id']) ? (int)$row['user_id'] : null
        );
    }
}
