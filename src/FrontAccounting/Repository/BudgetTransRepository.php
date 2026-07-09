<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\BudgetTrans;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class BudgetTransRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'budget_trans';
    public function findByAccount(string $account): array
    {
        $sql = "SELECT * FROM {$this->prefix}budget_trans WHERE account = ? ORDER BY tran_date";
        $rows = $this->db->query($sql, [$account]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByDimension(int $dimensionId): array
    {
        $sql = "SELECT * FROM {$this->prefix}budget_trans WHERE dimension_id = ? ORDER BY tran_date";
        $rows = $this->db->query($sql, [$dimensionId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByPeriod(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}budget_trans WHERE tran_date >= ? AND tran_date <= ? ORDER BY account, tran_date";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): BudgetTrans
    {
        return new BudgetTrans(
            (int)$row['id'],
            (int)$row['counter'],
            (string)$row['account'],
            (string)$row['tran_date'],
            (int)($row['dimension_id'] ?? 0),
            (int)($row['dimension2_id'] ?? 0),
            (float)$row['amount']
        );
    }

}
