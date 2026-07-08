<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\DebtorMaster;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class DebtorMasterRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $debtorNo): ?DebtorMaster
    {
        $sql = "SELECT * FROM {$this->prefix}debtors_master WHERE debtor_no = ?";
        $rows = $this->db->query($sql, [$debtorNo]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByRef(string $debtorRef): ?DebtorMaster
    {
        $sql = "SELECT * FROM {$this->prefix}debtors_master WHERE debtor_ref = ?";
        $rows = $this->db->query($sql, [$debtorRef]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}debtors_master WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}debtors_master WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function exists(int $debtorNo): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}debtors_master WHERE debtor_no = ?";
        $rows = $this->db->query($sql, [$debtorNo]);

        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    private function hydrate(array $row): DebtorMaster
    {
        return new DebtorMaster(
            (int)$row['debtor_no'],
            (string)$row['name'],
            (string)$row['debtor_ref'],
            isset($row['address']) ? (string)$row['address'] : null,
            (string)$row['tax_id'],
            (string)$row['curr_code'],
            (int)$row['sales_type'],
            (int)$row['dimension_id'],
            (int)$row['dimension2_id'],
            (int)$row['credit_status'],
            isset($row['payment_terms']) ? (int)$row['payment_terms'] : null,
            (float)$row['discount'],
            (float)$row['pymt_discount'],
            (float)$row['credit_limit'],
            (string)$row['notes'],
            (int)$row['inactive']
        );
    }

    protected function getTableName(): string
    {
        return 'debtors_master';
    }
}
