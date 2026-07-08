<?php

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GrnItemsRepository {
    use RepositoryTrait;
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

    protected function getTableName(): string
    {
        return 'grn_items';
    }
}
