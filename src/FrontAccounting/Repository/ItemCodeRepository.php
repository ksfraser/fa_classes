<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemCode;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemCodeRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'item_codes';
    public function findById(int $id): ?ItemCode
    {
        return $this->findOne(['id' => $id]);
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
        return $this->find(['stock_id' => $stockId], ['item_code' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['item_code' => 'ASC']);
    }

    protected function hydrate(array $row): ItemCode
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
