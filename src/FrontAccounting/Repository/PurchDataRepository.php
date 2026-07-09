<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PurchData;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchDataRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'purch_data';
    public function findById(int $id): ?PurchData
    {
        $sql = "SELECT * FROM {$this->prefix}purch_data WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findBySupplier(int $supplierId): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_data WHERE supplier_id = ? ORDER BY stock_id";
        $rows = $this->db->query($sql, [$supplierId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_data WHERE stock_id = ? ORDER BY price";
        $rows = $this->db->query($sql, [$stockId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findSupplierPrice(int $supplierId, string $stockId): ?PurchData
    {
        $sql = "SELECT * FROM {$this->prefix}purch_data WHERE supplier_id = ? AND stock_id = ? LIMIT 1";
        $rows = $this->db->query($sql, [$supplierId, $stockId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_data WHERE inactive = 0 ORDER BY stock_id";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): PurchData
    {
        return new PurchData(
            (int)$row['id'],
            (int)$row['supplier_id'],
            (string)$row['stock_id'],
            (float)$row['price'],
            isset($row['suppliers_uom']) ? (float)$row['suppliers_uom'] : 1.0,
            isset($row['conversion_factor']) ? (string)$row['conversion_factor'] : '1',
            isset($row['supplier_description']) ? (string)$row['supplier_description'] : '',
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
