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
        return $this->findOne(['id' => $id]);
    }

    public function findBySupplier(int $supplierId): array
    {
        return $this->find(['supplier_id' => $supplierId], ['stock_id' => 'ASC']);
    }

    public function findByStockId(string $stockId): array
    {
        return $this->find(['stock_id' => $stockId], ['price' => 'ASC']);
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
        return $this->find(['inactive' => 0], ['stock_id' => 'ASC']);
    }

    protected function hydrate(array $row): PurchData
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
