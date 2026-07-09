<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemUnit;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemUnitRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'item_units';
    public function findByAbbreviation(string $abbreviation): ?ItemUnit
    {
        return $this->findOne(['abbr' => $abbreviation]);
    }

    public function findAll(): array
    {
        return $this->find([], ['name' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): ItemUnit
    {
        return new ItemUnit(
            (string)$row['abbr'],
            (string)$row['name'],
            (int)($row['decimals'] ?? 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
