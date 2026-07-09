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
        return $this->findOne(['account_code' => $accountCode]);
    }

    public function findByType(int $accountType): array
    {
        return $this->find(['account_type' => $accountType], ['account_code' => 'ASC']);
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
        return $this->find(['inactive' => 0], ['account_code' => 'ASC']);
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

    protected function hydrate(array $row): ChartMaster
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
