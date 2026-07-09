<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Groups;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GroupsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'groups';
    public function findById(int $id): ?Groups
    {
        return $this->findOne(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->find([], ['description' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['description' => 'ASC']);
    }

    protected function hydrate(array $row): Groups
    {
        return new Groups(
            (int)$row['id'],
            (string)$row['description'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
