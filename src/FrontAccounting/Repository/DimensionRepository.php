<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Dimension;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class DimensionRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'dimensions';
    public function findById(int $id): ?Dimension
    {
        $sql = "SELECT * FROM {$this->prefix}dimensions WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}dimensions WHERE reference LIKE ? ORDER BY reference";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByType(int $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}dimensions WHERE type_ = ? ORDER BY reference";
        $rows = $this->db->query($sql, [$type]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOpen(): array
    {
        $sql = "SELECT * FROM {$this->prefix}dimensions WHERE closed = 0 ORDER BY reference";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}dimensions WHERE inactive = 0 ORDER BY reference";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Dimension
    {
        return new Dimension(
            (int)$row['id'],
            (string)$row['reference'],
            (string)$row['name'],
            (int)$row['type_'],
            (bool)(isset($row['closed']) ? (int)$row['closed'] : 0),
            isset($row['date_']) ? (string)$row['date_'] : null,
            isset($row['due_date']) ? (string)$row['due_date'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
