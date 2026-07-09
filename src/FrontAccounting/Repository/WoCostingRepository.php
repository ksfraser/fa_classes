<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoCosting;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoCostingRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'wo_costing';
    public function findById(int $id): ?WoCosting
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        return $this->find(['workorder_id' => $workOrderId], ['id' => 'ASC']);
    }

    public function findBySource(int $crType, int $crNo): array
    {
        return $this->find(['cr_type' => $crType, 'cr_no' => $crNo], ['id' => 'ASC']);
    }

    protected function hydrate(array $row): WoCosting
    {
        return new WoCosting(
            (int)$row['id'],
            (int)$row['workorder_id'],
            (int)$row['cr_type'],
            (int)$row['cr_no'],
            (string)$row['stock_id'],
            (float)$row['qty'],
            (float)$row['cost'],
            (string)($row['date_'] ?? '')
        );
    }

}
