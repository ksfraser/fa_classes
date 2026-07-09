<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Voided;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class VoidedRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'voided';
    public function findById(int $id): ?Voided
    {
        $sql = "SELECT * FROM {$this->prefix}voided WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByTransaction(int $type, int $typeNo): ?Voided
    {
        $sql = "SELECT * FROM {$this->prefix}voided WHERE type = ? AND type_no = ? LIMIT 1";
        $rows = $this->db->query($sql, [$type, $typeNo]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->prefix}voided WHERE user_id = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$userId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findVoidedInDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}voided WHERE date_ >= ? AND date_ <= ? ORDER BY date_, id";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByType(int $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}voided WHERE type = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$type]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Voided
    {
        return new Voided(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['type_no'],
            isset($row['date_']) ? (string)$row['date_'] : null,
            isset($row['memo_']) ? (string)$row['memo_'] : null,
            isset($row['user_id']) ? (int)$row['user_id'] : null,
            isset($row['user_email']) ? (string)$row['user_email'] : null
        );
    }

}
