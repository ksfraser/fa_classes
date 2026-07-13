<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesOrderDetailsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sales_order_details';

    private float $delta;

    public function __construct(DbAdapterInterface $db, float $delta = 0.005)
    {
        parent::__construct($db);
        $this->delta = $delta;
    }

    public function recalcQtySent(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}sales_order_details sod
            LEFT JOIN (
                SELECT dtd.src_id, SUM(dtd.qty_done) AS qty_sent
                FROM {$this->prefix}debtor_trans_details dtd
                JOIN {$this->prefix}debtor_trans dt
                    ON dt.trans_no = dtd.debtor_trans_no
                   AND dt.type     = dtd.debtor_trans_type
                WHERE dtd.debtor_trans_type = 13
                  AND dt.ov_amount != 0
                GROUP BY dtd.src_id
            ) del_sum ON del_sum.src_id = sod.id
            SET sod.qty_sent = COALESCE(del_sum.qty_sent, 0)
            WHERE ABS(sod.qty_sent - COALESCE(del_sum.qty_sent, 0)) > {$d}";

        return $this->db->execute($sql);
    }

    public function recalcInvoiced(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}sales_order_details sod
            LEFT JOIN (
                SELECT dtd.src_id, SUM(dtd.quantity) AS qty_invoiced
                FROM {$this->prefix}debtor_trans_details dtd
                JOIN {$this->prefix}debtor_trans dt
                    ON dt.trans_no = dtd.debtor_trans_no
                   AND dt.type     = dtd.debtor_trans_type
                WHERE dtd.debtor_trans_type = 10
                  AND dt.ov_amount != 0
                GROUP BY dtd.src_id
            ) inv_sum ON inv_sum.src_id = sod.id
            SET sod.invoiced = COALESCE(inv_sum.qty_invoiced, 0)
            WHERE ABS(sod.invoiced - COALESCE(inv_sum.qty_invoiced, 0)) > {$d}";

        return $this->db->execute($sql);
    }

    /**
     * Find sales-order detail lines where ordered qty exceeds qty_sent.
     *
     * @param  int|null $debtorNo   Optional customer filter
     * @param  int|null $branchCode Optional branch filter
     * @return array[]  List of associative arrays
     */
    public function findUndeliveredLines(?int $debtorNo = null, ?int $branchCode = null): array
    {
        $d = $this->delta;
        $where = '';
        $params = [];
        if ($debtorNo !== null) {
            $where .= ' AND so.debtor_no = ?';
            $params[] = $debtorNo;
        }
        if ($branchCode !== null) {
            $where .= ' AND so.branch_code = ?';
            $params[] = $branchCode;
        }
        $sql = "
            SELECT
                sod.order_no,
                so.reference        AS so_ref,
                so.ord_date,
                dm.debtor_no,
                dm.name             AS customer_name,
                sod.stk_code,
                sod.description,
                sod.quantity        AS qty_ordered,
                sod.qty_sent,
                sod.quantity - sod.qty_sent AS qty_outstanding,
                sod.unit_price
            FROM {$this->prefix}sales_order_details sod
            JOIN {$this->prefix}sales_orders so
                ON so.order_no   = sod.order_no
               AND so.trans_type = sod.trans_type
            JOIN {$this->prefix}debtors_master dm
                ON dm.debtor_no = so.debtor_no
            WHERE sod.quantity > sod.qty_sent + {$d}
              AND so.trans_type = 30
            {$where}
            ORDER BY dm.name, so.reference";

        return $this->db->query($sql, $params);
    }

}
