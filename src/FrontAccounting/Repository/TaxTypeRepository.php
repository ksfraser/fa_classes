<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TaxType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TaxTypeRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'tax_types';
    public function findById(int $id): ?TaxType
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}tax_types WHERE name LIKE ? ORDER BY name";
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

    protected function hydrate(array $row): TaxType
    {
        return new TaxType(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['tax_type_name'],
            (float)$row['rate'],
            (string)($row['sales_gl_code'] ?? ''),
            (string)($row['purchasing_gl_code'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
