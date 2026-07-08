<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PurchaseOrderDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchaseOrderDetailRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByOrder(int $orderNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_order_details
                WHERE order_no = ? ORDER BY po_detail_item";
        $rows = $this->db->query($sql, [$orderNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByItemCode(string $itemCode): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_order_details
                WHERE item_code = ? AND quantity_ordered - quantity_received > 0.005
                ORDER BY delivery_date";
        $rows = $this->db->query($sql, [$itemCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOpenByItem(string $itemCode): array
    {
        $sql = "SELECT pod.* FROM {$this->prefix}purch_order_details pod
                JOIN {$this->prefix}purch_orders po ON po.order_no = pod.order_no
                WHERE pod.item_code = ?
                  AND pod.quantity_ordered - pod.quantity_received > 0.005
                  AND po.total - po.alloc > 0.005
                ORDER BY pod.delivery_date";
        $rows = $this->db->query($sql, [$itemCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): PurchaseOrderDetail
    {
        return new PurchaseOrderDetail(
            (int)$row['po_detail_item'],
            (int)$row['order_no'],
            (string)$row['item_code'],
            isset($row['description']) ? (string)$row['description'] : null,
            (string)$row['delivery_date'],
            (float)$row['qty_invoiced'],
            (float)$row['unit_price'],
            (float)$row['act_price'],
            (float)$row['std_cost_unit'],
            (float)$row['quantity_ordered'],
            (float)$row['quantity_received']
        );
    }

    protected function getTableName(): string
    {
        return 'purch_order_details';
    }
}
