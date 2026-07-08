<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SupplierTransaction;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SupplierTransactionRepository
{
    /** @var DbAdapterInterface */
    private $db;
    /** @var string */
    private $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByTypeAndNo(int $type, int $transNo): ?SupplierTransaction
    {
        $sql = "SELECT * FROM {$this->prefix}supp_trans
                WHERE type = ? AND trans_no = ?";
        $rows = $this->db->query($sql, [$type, $transNo]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findBySupplier(int $supplierId): array
    {
        $sql = "SELECT * FROM {$this->prefix}supp_trans
                WHERE supplier_id = ? AND ov_amount != 0
                ORDER BY tran_date";
        $rows = $this->db->query($sql, [$supplierId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function isReferenceAlreadyUsed(int $supplierId, string $suppReference): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}supp_trans
                WHERE supplier_id = ? AND supp_reference = ? AND ov_amount != 0";
        $rows = $this->db->query($sql, [$supplierId, $suppReference]);

        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    public function getUnallocatedBySupplier(int $supplierId, float $delta = 0.005): array
    {
        $sql = "SELECT * FROM {$this->prefix}supp_trans
                WHERE supplier_id = ? AND ov_amount != 0
                  AND ABS(ov_amount + ov_gst + ov_discount - alloc) > {$delta}
                ORDER BY tran_date";
        $rows = $this->db->query($sql, [$supplierId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}supp_trans
                SET ov_amount = 0, ov_discount = 0, ov_gst = 0, alloc = 0
                WHERE type = ? AND trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    public function clear(int $type, int $transNo): int
    {
        $sql = "DELETE FROM {$this->prefix}supp_trans
                WHERE type = ? AND trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    private function hydrate(array $row): SupplierTransaction
    {
        return new SupplierTransaction(
            (int)$row['trans_no'],
            (int)$row['type'],
            (int)$row['supplier_id'],
            (string)$row['reference'],
            (string)$row['supp_reference'],
            (string)$row['tran_date'],
            (string)$row['due_date'],
            (float)$row['ov_amount'],
            (float)$row['ov_discount'],
            (float)$row['ov_gst'],
            (float)$row['rate'],
            (float)$row['alloc'],
            (int)$row['tax_included']
        );
    }
}
