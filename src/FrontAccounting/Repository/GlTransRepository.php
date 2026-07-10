<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\GlTrans;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GlTransRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'gl_trans';
    public function findByCounter(int $counter): ?GlTrans
    {
        return $this->findOne(['counter' => $counter]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        return $this->find(['type' => $type, 'type_no' => $typeNo], ['counter' => 'ASC']);
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
        return $this->find(['dimension_id' => $dimensionId], ['tran_date' => 'ASC', 'counter' => 'ASC']);
    }

    /**
     * Get the next counter value for a given transaction type+number.
     * Mimics FA core's SELECT MAX(counter)+1 from gl_trans.
     */
    public function getNextCounter(int $type, int $typeNo): int
    {
        $sql = "SELECT COALESCE(MAX(counter), 0) + 1 AS next_counter"
            . " FROM {$this->prefix}gl_trans WHERE type = ? AND type_no = ?";
        $result = $this->db->query($sql, [$type, $typeNo]);
        return (int)$result[0]['next_counter'];
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

    protected function hydrate(array $row): GlTrans
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
