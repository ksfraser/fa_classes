<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\GlTrans;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GlTransRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByCounter(int $counter): ?GlTrans
    {
        $sql = "SELECT * FROM {$this->prefix}gl_trans WHERE counter = ?";
        $rows = $this->db->query($sql, [$counter]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}gl_trans WHERE type = ? AND type_no = ? ORDER BY counter";
        $rows = $this->db->query($sql, [$type, $typeNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByAccount(string $accountCode, ?string $fromDate = null, ?string $toDate = null): array
    {
        $sql = "SELECT * FROM {$this->prefix}gl_trans WHERE account = ?";
        $params = [$accountCode];

        if ($fromDate !== null) {
            $sql .= " AND tran_date >= ?";
            $params[] = $fromDate;
        }
        if ($toDate !== null) {
            $sql .= " AND tran_date <= ?";
            $params[] = $toDate;
        }

        $sql .= " ORDER BY tran_date, counter";
        $rows = $this->db->query($sql, $params);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByDimension(int $dimensionId): array
    {
        $sql = "SELECT * FROM {$this->prefix}gl_trans WHERE dimension_id = ? ORDER BY tran_date, counter";
        $rows = $this->db->query($sql, [$dimensionId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findForPeriod(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}gl_trans WHERE tran_date >= ? AND tran_date <= ? ORDER BY tran_date, counter";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): GlTrans
    {
        return new GlTrans(
            (int)$row['counter'],
            (int)$row['type'],
            (int)$row['type_no'],
            isset($row['tran_date']) ? (string)$row['tran_date'] : null,
            (string)$row['account'],
            (string)$row['memo_'],
            (float)$row['amount'],
            isset($row['dimension_id']) ? (int)$row['dimension_id'] : 0,
            isset($row['dimension2_id']) ? (int)$row['dimension2_id'] : 0,
            isset($row['person_type_id']) ? ((int)$row['person_type_id'] !== 0 ? (int)$row['person_type_id'] : null) : null,
            isset($row['person_id']) ? ((int)$row['person_id'] !== 0 ? (int)$row['person_id'] : null) : null
        );
    }
}
