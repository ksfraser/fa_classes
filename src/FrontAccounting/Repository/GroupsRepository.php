<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Groups;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GroupsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'groups';
    public function findById(int $id): ?Groups
    {
        $sql = "SELECT * FROM {$this->prefix}groups WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}groups ORDER BY description";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}groups WHERE inactive = 0 ORDER BY description";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): Groups
    {
        return new Groups(
            (int)$row['id'],
            (string)$row['description'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
