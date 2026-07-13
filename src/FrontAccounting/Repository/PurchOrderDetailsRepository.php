<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchOrderDetailsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'purch_order_details';

    private float $delta;

    public function __construct(DbAdapterInterface $db, float $delta = 0.005)
    {
        parent::__construct($db);
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

    /**
     * Find purchase-order detail lines where ordered qty exceeds received qty.
     *
     * @param  int|null $supplierId Optional supplier filter
     * @return array[]  List of associative arrays
     */
    public function findUnreceivedLines(?int $supplierId = null): array
    {
        $d = $this->delta;
        $where = '';
        $params = [];
        if ($supplierId !== null) {
            $where = ' AND po.supplier_id = ?';
            $params[] = $supplierId;
        }
        $sql = "
            SELECT
                pod.order_no              AS po_no,
                po.reference              AS po_ref,
                po.ord_date,
                po.supplier_id,
                s.supp_name,
                pod.po_detail_item,
                pod.item_code,
                pod.description,
                pod.quantity_ordered,
                pod.quantity_received,
                pod.quantity_ordered - pod.quantity_received AS qty_outstanding,
                pod.unit_price,
                pod.qty_invoiced
            FROM {$this->prefix}purch_order_details pod
            JOIN {$this->prefix}purch_orders po        ON po.order_no = pod.order_no
            JOIN {$this->prefix}suppliers s            ON s.supplier_id = po.supplier_id
            WHERE pod.quantity_ordered > pod.quantity_received + {$d}
            {$where}
            ORDER BY s.supp_name, po.order_no";

        return $this->db->query($sql, $params);
    }

}
