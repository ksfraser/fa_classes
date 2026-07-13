<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\DebtorTransactionDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class DebtorTransactionDetailRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'debtor_trans_details';

    public function findByTransaction(int $type, int $transNo): array
    {
        $sql = "SELECT line.*, item.units, item.mb_flag
                FROM {$this->prefix}debtor_trans_details line
                LEFT JOIN {$this->prefix}stock_master item ON item.stock_id = line.stock_id
                WHERE debtor_trans_type = ? AND debtor_trans_no = ?
                ORDER BY id";
        $rows = $this->db->query($sql, [$type, $transNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findBySrcId(int $srcId): array
    {
        $sql = "SELECT line.*, item.units, item.mb_flag
                FROM {$this->prefix}debtor_trans_details line
                LEFT JOIN {$this->prefix}stock_master item ON item.stock_id = line.stock_id
                WHERE src_id = ?
                ORDER BY id";
        $rows = $this->db->query($sql, [$srcId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    /**
     * Find delivery-note items (type 13) where delivered qty exceeds invoiced
     * qty on the same sales-order detail line (via src_id).
     *
     * @param  int|null $debtorNo   Optional customer filter
     * @param  int|null $branchCode Optional branch filter
     * @return array[]  List of associative arrays
     */
    public function findUninvoicedDeliveries(?int $debtorNo = null, ?int $branchCode = null): array
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
                dt.trans_no           AS delivery_no,
                dt.reference          AS delivery_ref,
                dt.tran_date          AS delivery_date,
                dt.order_             AS sales_order_no,
                so.reference          AS so_ref,
                dt.debtor_no,
                dm.name               AS customer_name,
                dtd.stock_id,
                dtd.description,
                dtd.qty_done          AS qty_delivered,
                COALESCE(inv_sum.qty_invoiced, 0) AS qty_invoiced,
                dtd.qty_done - COALESCE(inv_sum.qty_invoiced, 0) AS uninvoiced_qty,
                dtd.unit_price
            FROM {$this->prefix}debtor_trans_details dtd
            JOIN {$this->prefix}debtor_trans dt
                ON dt.trans_no = dtd.debtor_trans_no
               AND dt.type     = dtd.debtor_trans_type
            JOIN {$this->prefix}debtors_master dm
                ON dm.debtor_no = dt.debtor_no
            LEFT JOIN {$this->prefix}sales_orders so
                ON so.order_no   = dt.order_
               AND so.trans_type = 30
            LEFT JOIN (
                SELECT src_id, SUM(quantity) AS qty_invoiced
                FROM {$this->prefix}debtor_trans_details
                WHERE debtor_trans_type = 10
                GROUP BY src_id
            ) inv_sum ON inv_sum.src_id = dtd.src_id
            WHERE dtd.debtor_trans_type = 13
              AND dt.ov_amount != 0
              AND dtd.qty_done > COALESCE(inv_sum.qty_invoiced, 0) + {$d}
            {$where}
            ORDER BY dm.name, dt.tran_date";

        return $this->db->query($sql, $params);
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}debtor_trans_details
                SET quantity = 0, unit_price = 0, unit_tax = 0,
                    discount_percent = 0, standard_cost = 0, src_id = 0
                WHERE debtor_trans_type = ? AND debtor_trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    protected function hydrate(array $row): DebtorTransactionDetail
    {
        return new DebtorTransactionDetail(
            (int)$row['id'],
            (int)$row['debtor_trans_no'],
            (int)$row['debtor_trans_type'],
            (string)$row['stock_id'],
            (string)$row['description'],
            (float)$row['unit_price'],
            (float)$row['unit_tax'],
            (float)$row['quantity'],
            (float)$row['discount_percent'],
            (float)$row['standard_cost'],
            (float)$row['qty_done'],
            (int)$row['src_id']
        );
    }

}
