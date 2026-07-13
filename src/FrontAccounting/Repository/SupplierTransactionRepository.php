<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SupplierTransaction;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SupplierTransactionRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'supp_trans';

    public function findByTypeAndNo(int $type, int $transNo): ?SupplierTransaction
    {
        return $this->findOne(['type' => $type, 'trans_no' => $transNo]);
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

    /**
     * Find supplier invoices (type 20) where alloc < total.
     *
     * @param  int|null $supplierId Optional supplier filter
     * @return array[]  List of associative arrays
     */
    public function findUnpaidInvoices(?int $supplierId = null): array
    {
        $d = 0.005;
        $where = '';
        $params = [];
        if ($supplierId !== null) {
            $where = ' AND st.supplier_id = ?';
            $params[] = $supplierId;
        }
        $sql = "
            SELECT
                st.trans_no            AS inv_no,
                st.reference           AS inv_ref,
                st.supp_reference,
                st.tran_date,
                st.due_date,
                st.supplier_id,
                s.supp_name,
                st.ov_amount,
                st.ov_gst,
                st.ov_discount,
                st.alloc,
                st.ov_amount + st.ov_gst + st.ov_discount AS total,
                st.ov_amount + st.ov_gst + st.ov_discount - st.alloc AS unpaid
            FROM {$this->prefix}supp_trans st
            JOIN {$this->prefix}suppliers s ON s.supplier_id = st.supplier_id
            WHERE st.type = 20
              AND st.ov_amount != 0
              AND st.alloc < st.ov_amount + st.ov_gst + st.ov_discount - {$d}
            {$where}
            ORDER BY s.supp_name, st.tran_date";

        return $this->db->query($sql, $params);
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

    protected function hydrate(array $row): SupplierTransaction
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
