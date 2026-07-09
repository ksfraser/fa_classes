<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoRequirement;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoRequirementRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'wo_requirements';
    public function findById(int $id): ?WoRequirement
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        return $this->find(['workorder_id' => $workOrderId], ['id' => 'ASC']);
    }

    public function findByStockId(string $stockId): array
    {
        return $this->find(['stock_id' => $stockId], ['workorder_id' => 'ASC']);
    }

    protected function hydrate(array $row): WoRequirement
    {
        return new WoRequirement(
            (int)$row['id'],
            (int)$row['workorder_id'],
            (string)$row['stock_id'],
            (float)$row['qty_required'],
            (float)($row['qty_issued'] ?? 0.0),
            (float)($row['qty_lost'] ?? 0.0),
            isset($row['date_']) ? (string)$row['date_'] : null
        );
    }

}
