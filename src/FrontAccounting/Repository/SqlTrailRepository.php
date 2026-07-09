<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SqlTrail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SqlTrailRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sql_trail';
    public function findById(int $id): ?SqlTrail
    {
        return $this->findOne(['id' => $id]);
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

    protected function hydrate(array $row): SqlTrail
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

}
