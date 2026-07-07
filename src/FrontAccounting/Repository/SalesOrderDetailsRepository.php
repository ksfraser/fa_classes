<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesOrderDetailsRepository
{
    /** @var DbAdapterInterface */
    private $db;
    /** @var string */
    private $prefix;
    /** @var float */
    private $delta;

    public function __construct(DbAdapterInterface $db, float $delta = 0.005)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
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
}
