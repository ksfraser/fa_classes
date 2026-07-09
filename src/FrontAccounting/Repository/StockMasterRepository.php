<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\StockMaster;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class StockMasterRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'stock_master';
    public function findById(string $stockId): ?StockMaster
    {
        return $this->findOne(['stock_id' => $stockId]);
    }

    public function findByCategory(int $categoryId): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_master
                WHERE category_id = ? AND inactive = 0
                ORDER BY stock_id";
        $rows = $this->db->query($sql, [$categoryId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['stock_id' => 'ASC']);
    }

    public function search(string $query): array
    {
        $like = '%' . $query . '%';
        $sql = "SELECT * FROM {$this->prefix}stock_master
                WHERE (stock_id LIKE ? OR description LIKE ?)
                  AND inactive = 0
                ORDER BY stock_id
                LIMIT 50";
        $rows = $this->db->query($sql, [$like, $like]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function exists(string $stockId): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}stock_master WHERE stock_id = ?";
        $rows = $this->db->query($sql, [$stockId]);

        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    protected function hydrate(array $row): StockMaster
    {
        return new StockMaster(
            (string)$row['stock_id'],
            (int)$row['category_id'],
            (int)$row['tax_type_id'],
            (string)$row['description'],
            (string)$row['long_description'],
            (string)$row['units'],
            (string)$row['mb_flag'],
            (string)$row['sales_account'],
            (string)$row['cogs_account'],
            (string)$row['inventory_account'],
            (string)$row['adjustment_account'],
            (string)$row['wip_account'],
            isset($row['dimension_id']) ? (int)$row['dimension_id'] : null,
            isset($row['dimension2_id']) ? (int)$row['dimension2_id'] : null,
            (float)$row['purchase_cost'],
            (float)$row['material_cost'],
            (float)$row['labour_cost'],
            (float)$row['overhead_cost'],
            (int)$row['inactive'],
            (int)$row['no_sale'],
            (int)$row['no_purchase'],
            (int)$row['editable']
        );
    }

}
