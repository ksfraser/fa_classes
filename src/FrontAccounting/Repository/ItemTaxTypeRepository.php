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
        $sql = "SELECT * FROM {$this->prefix}item_tax_types WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}item_tax_types WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_tax_types ORDER BY name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): ItemTaxType
    {
        return new ItemTaxType(
            (int)$row['id'],
            (string)$row['name'],
            (string)($row['long_name'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
