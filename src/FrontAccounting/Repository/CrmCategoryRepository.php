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
        return $this->findOne(['id' => $id]);
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
        return $this->findOne(['type' => $type, 'action' => $action]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['type' => 'ASC', 'name' => 'ASC']);
    }

    protected function hydrate(array $row): CrmCategory
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
