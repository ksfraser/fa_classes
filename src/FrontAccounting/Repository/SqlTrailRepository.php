<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SqlTrail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SqlTrailRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?SqlTrail
    {
        $sql = "SELECT * FROM {$this->prefix}sql_trail WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->prefix}sql_trail WHERE user_id = ? ORDER BY stamp DESC LIMIT 100";
        $rows = $this->db->query($sql, [$userId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}sql_trail WHERE stamp >= ? AND stamp <= ? ORDER BY stamp";
        $rows = $this->db->query($sql, [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): SqlTrail
    {
        return new SqlTrail(
            (int)$row['id'],
            (string)$row['sql_req'],
            (string)$row['stamp'],
            (int)$row['user_id'],
            (string)($row['error_no'] ?? ''),
            (string)($row['msg'] ?? '')
        );
    }

    protected function getTableName(): string
    {
        return 'sql_trail';
    }
}
