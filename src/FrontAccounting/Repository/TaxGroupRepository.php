<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TaxGroup;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TaxGroupRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'tax_groups';
    public function findById(int $id): ?TaxGroup
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}tax_groups WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): TaxGroup
    {
        return new TaxGroup(
            (int)$row['id'],
            (string)$row['name'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
