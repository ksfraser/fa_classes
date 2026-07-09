<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoManufacture;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoManufactureRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'wo_manufacture';
    public function findById(int $id): ?WoManufacture
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        return $this->find(['workorder_id' => $workOrderId], ['id' => 'ASC']);
    }

    public function findByStockId(string $stockId): array
    {
        return $this->find(['stock_id' => $stockId], ['date_' => 'DESC']);
    }

    protected function hydrate(array $row): WoManufacture
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
