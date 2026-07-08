<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WorkCentre;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WorkCentreRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?WorkCentre
    {
        $sql = "SELECT * FROM {$this->prefix}workcentres WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}workcentres WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}workcentres ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WorkCentre
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
