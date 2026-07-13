<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Service\Contracts\OrderToDeliveryService;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of OrderToDeliveryService.
 *
 * Uses DbAdapter directly for read-only analysis queries spanning
 * purch_order_details, purch_orders, and suppliers.
 *
 * ┌──────────────────────────────────────────────────────────────┐
 * │                OrderToDeliveryServiceStandard                 │
 * ├──────────────────────────────────────────────────────────────┤
 * │  - db: DbAdapterInterface                                    │
 * ├──────────────────────────────────────────────────────────────┤
 * │  + getItemDelays(?string $itemCode): array                   │
 * │  + getSupplierDelays(?string $supplierName): array           │
 * │  + getOrderDeliveryDetails(?int $orderNo): array             │
 * └──────────────────────────────────────────────────────────────┘
 */
final class OrderToDeliveryServiceStandard implements OrderToDeliveryService
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function getItemDelays(?string $itemCode = null): array
    {
        $parts = [
            "SELECT d.item_code AS stock_id, s.supp_name AS supplier,",
            "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days",
            "FROM {$this->prefix}purch_order_details d,",
            "{$this->prefix}purch_orders o,",
            "{$this->prefix}suppliers s",
            "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id",
        ];

        $params = [];
        if ($itemCode !== null) {
            $parts[] = "AND d.item_code = ?";
            $params[] = $itemCode;
        }

        $parts[] = "ORDER BY d.item_code, s.supp_name";

        $rows = $this->db->query(implode(' ', $parts), $params);

        return array_map(function (array $row): array {
            return [
                'stock_id' => (string)$row['stock_id'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
            ];
        }, $rows);
    }

    public function getSupplierDelays(?string $supplierName = null): array
    {
        $parts = [
            "SELECT d.order_no AS order_number, s.supp_name AS supplier,",
            "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days",
            "FROM {$this->prefix}purch_order_details d,",
            "{$this->prefix}purch_orders o,",
            "{$this->prefix}suppliers s",
            "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id",
        ];

        $params = [];
        if ($supplierName !== null) {
            $parts[] = "AND s.supp_name = ?";
            $params[] = $supplierName;
        }

        $parts[] = "GROUP BY d.order_no ORDER BY d.item_code, s.supp_name";

        $rows = $this->db->query(implode(' ', $parts), $params);

        return array_map(function (array $row): array {
            return [
                'order_number' => (int)$row['order_number'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
            ];
        }, $rows);
    }

    public function getOrderDeliveryDetails(?int $orderNo = null): array
    {
        $parts = [
            "SELECT d.order_no AS order_number, s.supp_name AS supplier,",
            "ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days,",
            "o.ord_date AS order_date, d.delivery_date AS delivery_date,",
            "d.item_code AS stock_id, d.quantity_ordered AS quantity_ordered,",
            "d.quantity_received AS quantity_received",
            "FROM {$this->prefix}purch_order_details d,",
            "{$this->prefix}purch_orders o,",
            "{$this->prefix}suppliers s",
            "WHERE o.order_no = d.order_no AND o.supplier_id = s.supplier_id",
        ];

        $params = [];
        if ($orderNo !== null) {
            $parts[] = "AND d.order_no = ?";
            $params[] = $orderNo;
        }

        $parts[] = "GROUP BY d.order_no ORDER BY d.item_code, s.supp_name";

        $rows = $this->db->query(implode(' ', $parts), $params);

        return array_map(function (array $row): array {
            return [
                'order_number' => (int)$row['order_number'],
                'supplier' => (string)$row['supplier'],
                'days' => (int)$row['days'],
                'order_date' => (string)$row['order_date'],
                'delivery_date' => (string)$row['delivery_date'],
                'stock_id' => (string)$row['stock_id'],
                'quantity_ordered' => (float)$row['quantity_ordered'],
                'quantity_received' => (float)$row['quantity_received'],
            ];
        }, $rows);
    }
}
