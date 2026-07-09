<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\QuickEntry;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class QuickEntryRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'quick_entries';
    public function findById(int $id): ?QuickEntry
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entries WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByType(int $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entries WHERE type = ? ORDER BY description";
        $rows = $this->db->query($sql, [$type]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entries WHERE inactive = 0 ORDER BY description";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entries ORDER BY description";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): QuickEntry
    {
        return new QuickEntry(
            (int)$row['id'],
            (string)$row['description'],
            (int)$row['type'],
            (int)$row['base_amount'],
            (string)($row['base_amount_type'] ?? ''),
            (string)($row['base_desc'] ?? '')
        );
    }

}
