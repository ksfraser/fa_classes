<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ChartMaster;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ChartMasterRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'chart_master';
    public function findByCode(string $accountCode): ?ChartMaster
    {
        $sql = "SELECT * FROM {$this->prefix}chart_master WHERE account_code = ?";
        $rows = $this->db->query($sql, [$accountCode]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByType(int $accountType): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_master WHERE account_type = ? ORDER BY account_code";
        $rows = $this->db->query($sql, [$accountType]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_master WHERE account_name LIKE ? ORDER BY account_code";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_master WHERE inactive = 0 ORDER BY account_code";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findBankAccounts(): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_master WHERE bank_code IS NOT NULL AND inactive = 0 ORDER BY account_code";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function exists(string $accountCode): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}chart_master WHERE account_code = ?";
        $rows = $this->db->query($sql, [$accountCode]);

        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    private function hydrate(array $row): ChartMaster
    {
        return new ChartMaster(
            (string)$row['account_code'],
            (int)$row['account_type'],
            (string)$row['account_name'],
            isset($row['bank_code']) ? (string)$row['bank_code'] : null,
            isset($row['bank_description']) ? (string)$row['bank_description'] : null,
            (bool)(isset($row['show_in_trial_balance']) ? (int)$row['show_in_trial_balance'] : 1),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
