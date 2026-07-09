<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\StockFaClass;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class StockFaClassRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'stock_fa_class';
    public function findById(int $id): ?StockFaClass
    {
        return $this->findOne(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->find([], ['name' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): StockFaClass
    {
        return new StockFaClass(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['description'],
            isset($row['depreciation_rate']) ? (float)$row['depreciation_rate'] : null,
            (string)($row['fa_account_code'] ?? ''),
            (string)($row['depreciation_account_code'] ?? ''),
            (string)($row['accum_depreciation_account_code'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
