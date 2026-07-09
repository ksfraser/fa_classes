<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemTaxType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemTaxTypeRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'item_tax_types';
    public function findById(int $id): ?ItemTaxType
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_tax_types WHERE name LIKE ? ORDER BY name";
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

    protected function hydrate(array $row): ItemTaxType
    {
        return new ItemTaxType(
            (int)$row['id'],
            (string)$row['name'],
            (string)($row['long_name'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
