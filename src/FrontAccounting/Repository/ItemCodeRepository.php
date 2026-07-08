<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemCode;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemCodeRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?ItemCode
    {
        $sql = "SELECT * FROM {$this->prefix}item_codes WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByItemCode(string $itemCode): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_codes WHERE item_code LIKE ? ORDER BY stock_id";
        $rows = $this->db->query($sql, ['%' . $itemCode . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_codes WHERE stock_id = ? ORDER BY item_code";
        $rows = $this->db->query($sql, [$stockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_codes WHERE inactive = 0 ORDER BY item_code";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): ItemCode
    {
        return new ItemCode(
            (int)$row['id'],
            (string)$row['item_code'],
            (string)$row['stock_id'],
            isset($row['description']) ? (string)$row['description'] : null,
            isset($row['category_id']) ? (string)$row['category_id'] : null,
            (float)($row['quantity'] ?? 1.0),
            (bool)(isset($row['is_foreign']) ? (int)$row['is_foreign'] : 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }
}
