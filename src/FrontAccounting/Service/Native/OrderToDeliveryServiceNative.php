<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\OrderToDeliveryService;

/**
 * @since 2026-07-10
 * Native implementation of OrderToDeliveryService using db_query()/db_fetch().
 *
 * Replicates the query logic from the legacy fa_order_to_delivery class
 * (which extended the external table_interface) without its bugs:
 *   - Method name mismatches fixed
 *   - Malformed WHERE clauses fixed
 *   - Uses db_query()/db_fetch() directly instead of select_tables()
 *
 * ┌──────────────────────────────────────────────────────────────┐
 * │                 OrderToDeliveryServiceNative                  │
 * ├──────────────────────────────────────────────────────────────┤
 * │  + getItemDelays(?string $itemCode): array                   │
 * │  + getSupplierDelays(?string $supplierName): array           │
 * │  + getOrderDeliveryDetails(?int $orderNo): array             │
 * └──────────────────────────────────────────────────────────────┘
 */
class OrderToDeliveryServiceNative implements OrderToDeliveryService
{
    private function getPrefix(): string
    {
        return defined('TB_PREF') ? TB_PREF : '';
    }

    public function getItemDelays(?string $itemCode = null): array
    {
        $prefix = $this->getPrefix();
        $sql = "SELECT d.item_code AS stock_id, s.supp_name AS supplier, "
            . "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days "
            . "FROM {$prefix}purch_order_details d, {$prefix}purch_orders o, {$prefix}suppliers s "
            . "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id";

        if ($itemCode !== null) {
            $escaped = \db_escape($itemCode);
            $sql .= " AND d.item_code = '{$escaped}'";
        }

        $sql .= " ORDER BY d.item_code, s.supp_name";

        $result = \db_query($sql);
        $rows = [];
        while ($row = \db_fetch($result)) {
            $rows[] = [
                'stock_id' => (string)$row['stock_id'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
            ];
        }
        return $rows;
    }

    public function getSupplierDelays(?string $supplierName = null): array
    {
        $prefix = $this->getPrefix();
        $sql = "SELECT d.order_no AS order_number, s.supp_name AS supplier, "
            . "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days "
            . "FROM {$prefix}purch_order_details d, {$prefix}purch_orders o, {$prefix}suppliers s "
            . "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id";

        if ($supplierName !== null) {
            $escaped = \db_escape($supplierName);
            $sql .= " AND s.supp_name = '{$escaped}'";
        }

        $sql .= " GROUP BY d.order_no ORDER BY d.item_code, s.supp_name";

        $result = \db_query($sql);
        $rows = [];
        while ($row = \db_fetch($result)) {
            $rows[] = [
                'order_number' => (int)$row['order_number'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
            ];
        }
        return $rows;
    }

    public function getOrderDeliveryDetails(?int $orderNo = null): array
    {
        $prefix = $this->getPrefix();
        $sql = "SELECT d.order_no AS order_number, s.supp_name AS supplier, "
            . "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days, "
            . "o.ord_date AS order_date, d.delivery_date AS delivery_date, "
            . "d.item_code AS stock_id, d.quantity_ordered AS quantity_ordered, "
            . "d.quantity_received AS quantity_received "
            . "FROM {$prefix}purch_order_details d, {$prefix}purch_orders o, {$prefix}suppliers s "
            . "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id";

        if ($orderNo !== null) {
            $sql .= " AND d.order_no = " . (int)$orderNo;
        }

        $sql .= " GROUP BY d.order_no ORDER BY d.item_code, s.supp_name";

        $result = \db_query($sql);
        $rows = [];
        while ($row = \db_fetch($result)) {
            $rows[] = [
                'order_number' => (int)$row['order_number'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
                'order_date' => (string)$row['order_date'],
                'delivery_date' => (string)$row['delivery_date'],
                'stock_id' => (string)$row['stock_id'],
                'quantity_ordered' => (float)$row['quantity_ordered'],
                'quantity_received' => (float)$row['quantity_received'],
            ];
        }
        return $rows;
    }
}
