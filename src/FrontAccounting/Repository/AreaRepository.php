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
        $sql = "SELECT * FROM {$this->prefix}areas WHERE area_code = ?";
        $rows = $this->db->query($sql, [$areaCode]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}areas WHERE inactive = 0 ORDER BY description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}areas ORDER BY description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Area
    {
        return new Area(
            (int)$row['area_code'],
            (string)$row['description'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
