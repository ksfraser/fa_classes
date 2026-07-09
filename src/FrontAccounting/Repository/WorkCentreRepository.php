<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WorkCentre;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WorkCentreRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'workcentres';
    public function findById(int $id): ?WorkCentre
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}workcentres WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
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

    protected function hydrate(array $row): WorkCentre
    {
        return new WorkCentre(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['description'],
            isset($row['overhead_cost']) ? (float)$row['overhead_cost'] : null,
            isset($row['labour_cost']) ? (float)$row['labour_cost'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
