<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesTypeRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sales_types';
    public function findById(int $id): ?SalesType
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByType(string $salesType): ?SalesType
    {
        $sql = "SELECT * FROM {$this->prefix}sales_types WHERE sales_type = ? LIMIT 1";
        $rows = $this->db->query($sql, [$salesType]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['sales_type' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['sales_type' => 'ASC']);
    }

    protected function hydrate(array $row): SalesType
    {
        return new SalesType(
            (int)$row['id'],
            (string)$row['sales_type'],
            (float)($row['tax_included'] ?? 0),
            (float)($row['factor'] ?? 1.0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
