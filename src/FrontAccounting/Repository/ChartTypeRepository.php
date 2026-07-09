<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ChartType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ChartTypeRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'chart_types';
    public function findById(int $id): ?ChartType
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByClass(int $classId): array
    {
        return $this->find(['class_id' => $classId], ['name' => 'ASC']);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_types WHERE name LIKE ? ORDER BY name";
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

    protected function hydrate(array $row): ChartType
    {
        return new ChartType(
            (int)$row['id'],
            (string)$row['name'],
            (int)$row['class_id'],
            isset($row['parent']) ? ($row['parent'] !== '' ? (int)$row['parent'] : null) : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
