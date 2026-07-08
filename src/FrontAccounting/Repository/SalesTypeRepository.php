<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesTypeRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?SalesType
    {
        $sql = "SELECT * FROM {$this->prefix}sales_types WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}sales_types WHERE inactive = 0 ORDER BY sales_type";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_types ORDER BY sales_type";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): SalesType
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
