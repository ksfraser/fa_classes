<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\AuditTrail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class AuditTrailRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'audit_trail';
    public function findById(int $id): ?AuditTrail
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByTransaction(int $type, int $transNo): array
    {
        return $this->find(['type' => $type, 'trans_no' => $transNo], ['stamp' => 'ASC']);
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->prefix}audit_trail WHERE user = ? ORDER BY stamp DESC LIMIT 100";
        $rows = $this->db->query($sql, [$userId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}audit_trail WHERE stamp >= ? AND stamp <= ? ORDER BY stamp";
        $rows = $this->db->query($sql, [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): AuditTrail
    {
        return new AuditTrail(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['trans_no'],
            isset($row['user']) ? (int)$row['user'] : null,
            isset($row['stamp']) ? (string)$row['stamp'] : null,
            isset($row['description']) ? (string)$row['description'] : null,
            isset($row['sql']) ? (string)$row['sql'] : null
        );
    }

}
