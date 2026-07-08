<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\BankTransaction;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class BankTransactionRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?BankTransaction
    {
        $sql = "SELECT * FROM {$this->prefix}bank_trans WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByBankAccount(string $bankAccount): array
    {
        $sql = "SELECT * FROM {$this->prefix}bank_trans WHERE bank_act = ? ORDER BY trans_date DESC";
        $rows = $this->db->query($sql, [$bankAccount]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByTransaction(int $type, int $transNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}bank_trans WHERE type = ? AND trans_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$type, $transNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findUnreconciled(string $bankAccount): array
    {
        $sql = "SELECT * FROM {$this->prefix}bank_trans WHERE bank_act = ? AND reconciled = 0 ORDER BY trans_date DESC";
        $rows = $this->db->query($sql, [$bankAccount]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByDateRange(string $bankAccount, string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}bank_trans WHERE bank_act = ? AND trans_date >= ? AND trans_date <= ? ORDER BY trans_date";
        $rows = $this->db->query($sql, [$bankAccount, $fromDate, $toDate]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): BankTransaction
    {
        return new BankTransaction(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['trans_no'],
            (string)$row['bank_act'],
            (string)$row['ref'],
            isset($row['statement_date']) ? (string)$row['statement_date'] : null,
            (float)$row['amount'],
            isset($row['dimension_id']) ? ((int)$row['dimension_id'] !== 0 ? (int)$row['dimension_id'] : null) : null,
            isset($row['dimension2_id']) ? ((int)$row['dimension2_id'] !== 0 ? (int)$row['dimension2_id'] : null) : null,
            isset($row['person_type']) ? (string)$row['person_type'] : null,
            isset($row['person_id']) ? ((int)$row['person_id'] !== 0 ? (int)$row['person_id'] : null) : null,
            isset($row['trans_date']) ? (string)$row['trans_date'] : null,
            (bool)(isset($row['reconciled']) ? (int)$row['reconciled'] : 0)
        );
    }
}
