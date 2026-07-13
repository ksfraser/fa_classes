<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\DebtorTransaction;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class DebtorTransactionRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'debtor_trans';

    public function findByTypeAndNo(int $type, int $transNo): ?DebtorTransaction
    {
        return $this->findOne(['type' => $type, 'trans_no' => $transNo]);
    }

    public function findByCustomer(int $debtorNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}debtor_trans
                WHERE debtor_no = ? AND ov_amount != 0 AND type != 13
                ORDER BY tran_date";
        $rows = $this->db->query($sql, [$debtorNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function getNextTransNo(int $type): int
    {
        $sql = "SELECT COALESCE(MAX(trans_no), 0) + 1 AS next_no"
            . " FROM {$this->prefix}debtor_trans WHERE type = ?";
        $rows = $this->db->query($sql, [$type]);
        return (int)$rows[0]['next_no'];
    }

    public function findByOrder(int $orderNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}debtor_trans
                WHERE order_ = ? AND ov_amount != 0
                ORDER BY tran_date";
        $rows = $this->db->query($sql, [$orderNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function getUnallocatedByCustomer(int $debtorNo, float $delta = 0.005): array
    {
        $sql = "SELECT * FROM {$this->prefix}debtor_trans
                WHERE debtor_no = ? AND ov_amount != 0 AND type != 13
                  AND ABS(ov_amount + ov_gst + ov_freight + ov_freight_tax + ov_discount - alloc) > {$delta}
                ORDER BY tran_date";
        $rows = $this->db->query($sql, [$debtorNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    /**
     * Find customer invoices (type 10) where alloc < total.
     *
     * @param  int|null $debtorNo   Optional customer filter
     * @param  int|null $branchCode Optional branch filter
     * @return array[]  List of associative arrays
     */
    public function findUnpaidInvoices(?int $debtorNo = null, ?int $branchCode = null): array
    {
        $d = 0.005;
        $where = '';
        $params = [];
        if ($debtorNo !== null) {
            $where .= ' AND dt.debtor_no = ?';
            $params[] = $debtorNo;
        }
        if ($branchCode !== null) {
            $where .= ' AND dt.branch_code = ?';
            $params[] = $branchCode;
        }
        $sql = "
            SELECT
                dt.trans_no            AS inv_no,
                dt.reference,
                dt.tran_date,
                dt.due_date,
                dt.debtor_no,
                dm.name                AS customer_name,
                dt.ov_amount,
                dt.ov_gst,
                dt.ov_freight,
                dt.ov_freight_tax,
                dt.ov_discount,
                dt.alloc,
                dt.ov_amount + dt.ov_gst + dt.ov_freight
                    + dt.ov_freight_tax + dt.ov_discount  AS total,
                dt.ov_amount + dt.ov_gst + dt.ov_freight
                    + dt.ov_freight_tax + dt.ov_discount - dt.alloc AS unpaid
            FROM {$this->prefix}debtor_trans dt
            JOIN {$this->prefix}debtors_master dm
                ON dm.debtor_no = dt.debtor_no
            WHERE dt.type = 10
              AND dt.ov_amount != 0
              AND dt.alloc < dt.ov_amount + dt.ov_gst + dt.ov_freight
                  + dt.ov_freight_tax + dt.ov_discount - {$d}
            {$where}
            ORDER BY dm.name, dt.tran_date";

        return $this->db->query($sql, $params);
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}debtor_trans
                SET ov_amount = 0, ov_discount = 0, ov_gst = 0,
                    ov_freight = 0, ov_freight_tax = 0, alloc = 0,
                    prep_amount = 0, version = version + 1
                WHERE type = ? AND trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    public function clear(int $type, int $transNo): int
    {
        $sql = "DELETE FROM {$this->prefix}debtor_trans
                WHERE type = ? AND trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    protected function hydrate(array $row): DebtorTransaction
    {
        return new DebtorTransaction(
            (int)$row['trans_no'],
            (int)$row['type'],
            (int)$row['debtor_no'],
            (int)$row['branch_code'],
            (string)$row['tran_date'],
            (string)$row['due_date'],
            (string)$row['reference'],
            (int)$row['order_'],
            (float)$row['ov_amount'],
            (float)$row['ov_gst'],
            (float)$row['ov_freight'],
            (float)$row['ov_freight_tax'],
            (float)$row['ov_discount'],
            (float)$row['alloc'],
            (float)$row['prep_amount'],
            (float)$row['rate'],
            (int)$row['ship_via'],
            (int)$row['dimension_id'],
            (int)$row['dimension2_id'],
            (int)$row['payment_terms'],
            (int)$row['tax_included']
        );
    }

}
