<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GrnItemsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'grn_items';

    private float $delta;

    public function __construct(DbAdapterInterface $db, float $delta = 0.005)
    {
        parent::__construct($db);
        $this->delta = $delta;
    }

    public function recalcQtyInv(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}grn_items g
            LEFT JOIN (
                SELECT si.grn_item_id, SUM(si.quantity) AS qty_invoiced
                FROM {$this->prefix}supp_invoice_items si
                JOIN {$this->prefix}supp_trans st
                    ON st.trans_no = si.supp_trans_no
                   AND st.type     = si.supp_trans_type
                WHERE si.supp_trans_type = 20
                  AND st.ov_amount != 0
                GROUP BY si.grn_item_id
            ) inv_sum ON inv_sum.grn_item_id = g.id
            SET g.quantity_inv = COALESCE(inv_sum.qty_invoiced, 0)
            WHERE ABS(g.quantity_inv - COALESCE(inv_sum.qty_invoiced, 0)) > {$d}";

        return $this->db->execute($sql);
    }

    /**
     * Find GRN items where received qty exceeds invoiced qty.
     *
     * @param  int|null $supplierId Optional supplier filter
     * @return array[]  List of associative arrays
     */
    public function findUninvoicedLines(?int $supplierId = null): array
    {
        $d = $this->delta;
        $where = '';
        $params = [];
        if ($supplierId !== null) {
            $where = ' AND gb.supplier_id = ?';
            $params[] = $supplierId;
        }
        $sql = "
            SELECT
                g.id                AS grn_item_id,
                gb.id               AS grn_batch_id,
                gb.delivery_date,
                gb.reference        AS grn_ref,
                gb.purch_order_no,
                gb.supplier_id,
                s.supp_name,
                g.item_code,
                g.description,
                g.qty_recd,
                g.quantity_inv,
                g.qty_recd - g.quantity_inv AS uninvoiced_qty
            FROM {$this->prefix}grn_batch gb
            JOIN {$this->prefix}grn_items g    ON g.grn_batch_id = gb.id
            JOIN {$this->prefix}suppliers s    ON s.supplier_id = gb.supplier_id
            WHERE g.qty_recd > g.quantity_inv + {$d}
            {$where}
            ORDER BY s.supp_name, gb.delivery_date";

        return $this->db->query($sql, $params);
    }

}
