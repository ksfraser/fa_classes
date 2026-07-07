<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchOrderDetailsRepository
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

    public function recalcQtyInvoiced(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}purch_order_details pod
            LEFT JOIN (
                SELECT si.po_detail_item_id, SUM(si.quantity) AS qty_invoiced
                FROM {$this->prefix}supp_invoice_items si
                JOIN {$this->prefix}supp_trans st
                    ON st.trans_no = si.supp_trans_no
                   AND st.type     = si.supp_trans_type
                WHERE si.supp_trans_type = 20
                  AND st.ov_amount != 0
                GROUP BY si.po_detail_item_id
            ) inv_sum ON inv_sum.po_detail_item_id = pod.po_detail_item
            SET pod.qty_invoiced = COALESCE(inv_sum.qty_invoiced, 0)
            WHERE ABS(pod.qty_invoiced - COALESCE(inv_sum.qty_invoiced, 0)) > {$d}";

        return $this->db->execute($sql);
    }

    public function recalcQtyReceived(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}purch_order_details pod
            LEFT JOIN (
                SELECT po_detail_item, SUM(qty_recd) AS qty_recd
                FROM {$this->prefix}grn_items
                GROUP BY po_detail_item
            ) grn_sum ON grn_sum.po_detail_item = pod.po_detail_item
            SET pod.quantity_received = COALESCE(grn_sum.qty_recd, 0)
            WHERE ABS(pod.quantity_received - COALESCE(grn_sum.qty_recd, 0)) > {$d}";

        return $this->db->execute($sql);
    }
}
