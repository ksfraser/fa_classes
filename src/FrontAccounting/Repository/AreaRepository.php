<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Area;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class AreaRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'areas';
    public function findById(int $areaCode): ?Area
    {
        return $this->findOne(['area_code' => $areaCode]);
    }

    public function findByDescription(string $description): array
    {
        $sql = "SELECT * FROM {$this->prefix}areas WHERE description LIKE ? ORDER BY description";
        $rows = $this->db->query($sql, ['%' . $description . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['description' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['description' => 'ASC']);
    }

    protected function hydrate(array $row): Area
    {
        return new Area(
            (int)$row['area_code'],
            (string)$row['description'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
