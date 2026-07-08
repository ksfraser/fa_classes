<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\RefLines;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class RefLinesRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByType(int $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}reflines WHERE type = ? ORDER BY reference";
        $rows = $this->db->query($sql, [$type]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}reflines WHERE reference LIKE ? ORDER BY type";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}reflines WHERE inactive = 0 ORDER BY type, reference";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): RefLines
    {
        return new RefLines(
            (int)$row['id'],
            (int)$row['type'],
            (string)$row['reference'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'reflines';
    }
}
