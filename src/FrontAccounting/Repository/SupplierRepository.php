<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Supplier;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SupplierRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $supplierId): ?Supplier
    {
        $sql = "SELECT * FROM {$this->prefix}suppliers WHERE supplier_id = ?";
        $rows = $this->db->query($sql, [$supplierId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByRef(string $suppRef): ?Supplier
    {
        $sql = "SELECT * FROM {$this->prefix}suppliers WHERE supp_ref = ?";
        $rows = $this->db->query($sql, [$suppRef]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}suppliers WHERE supp_name LIKE ? ORDER BY supp_name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}suppliers WHERE inactive = 0 ORDER BY supp_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function exists(int $supplierId): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}suppliers WHERE supplier_id = ?";
        $rows = $this->db->query($sql, [$supplierId]);

        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    private function hydrate(array $row): Supplier
    {
        return new Supplier(
            (int)$row['supplier_id'],
            (string)$row['supp_name'],
            (string)$row['supp_ref'],
            (string)$row['address'],
            (string)$row['supp_address'],
            (string)$row['gst_no'],
            (string)$row['contact'],
            (string)$row['supp_account_no'],
            (string)$row['website'],
            (string)$row['bank_account'],
            isset($row['curr_code']) ? (string)$row['curr_code'] : null,
            isset($row['payment_terms']) ? (int)$row['payment_terms'] : null,
            (int)$row['tax_included'],
            (int)$row['dimension_id'],
            (int)$row['dimension2_id'],
            isset($row['tax_group_id']) ? (int)$row['tax_group_id'] : null,
            (float)$row['credit_limit'],
            (string)$row['purchase_account'],
            (string)$row['payable_account'],
            (string)$row['payment_discount_account'],
            (string)$row['notes'],
            (int)$row['inactive']
        );
    }
}
