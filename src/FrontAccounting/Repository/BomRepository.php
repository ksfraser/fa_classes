<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Bom;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class BomRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'bom';
    public function findById(int $id): ?Bom
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByParent(string $parentStockId): array
    {
        return $this->find(['parent' => $parentStockId], ['id' => 'ASC']);
    }

    public function findByComponent(string $componentStockId): array
    {
        return $this->find(['component' => $componentStockId], ['parent' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['parent' => 'ASC']);
    }

    protected function hydrate(array $row): Bom
    {
        return new Bom(
            (int)$row['id'],
            (string)$row['parent'],
            (string)$row['component'],
            (float)$row['quantity'],
            (float)($row['labour_cost'] ?? 0.0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
