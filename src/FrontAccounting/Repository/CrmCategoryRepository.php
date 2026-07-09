<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CrmCategory;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CrmCategoryRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'crm_categories';
    public function findById(int $id): ?CrmCategory
    {
        $sql = "SELECT * FROM {$this->prefix}crm_categories WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByType(string $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_categories WHERE type = ? AND inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql, [$type]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByTypeAndAction(string $type, string $action): ?CrmCategory
    {
        $sql = "SELECT * FROM {$this->prefix}crm_categories WHERE type = ? AND action = ?";
        $rows = $this->db->query($sql, [$type, $action]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_categories WHERE inactive = 0 ORDER BY type, name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): CrmCategory
    {
        return new CrmCategory(
            (int)$row['id'],
            (string)$row['type'],
            (string)$row['action'],
            (string)$row['name'],
            (string)$row['description'],
            (int)$row['system'],
            (int)$row['inactive']
        );
    }

}
