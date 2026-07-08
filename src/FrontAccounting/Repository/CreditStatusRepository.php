<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CreditStatus;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CreditStatusRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?CreditStatus
    {
        $sql = "SELECT * FROM {$this->prefix}credit_status WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}credit_status WHERE inactive = 0 ORDER BY reason_description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findDissallowInvoices(): array
    {
        $sql = "SELECT * FROM {$this->prefix}credit_status WHERE dissallow_invoices = 1 AND inactive = 0 ORDER BY reason_description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}credit_status ORDER BY reason_description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): CreditStatus
    {
        return new CreditStatus(
            (int)$row['id'],
            (string)$row['reason_description'],
            (bool)(isset($row['dissallow_invoices']) ? (int)$row['dissallow_invoices'] : 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }
}
