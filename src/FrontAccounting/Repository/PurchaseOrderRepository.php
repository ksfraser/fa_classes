<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PurchaseOrder;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchaseOrderRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $orderNo): ?PurchaseOrder
    {
        $sql = "SELECT * FROM {$this->prefix}purch_orders WHERE order_no = ?";
        $rows = $this->db->query($sql, [$orderNo]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findBySupplier(int $supplierId): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_orders
                WHERE supplier_id = ? ORDER BY ord_date DESC";
        $rows = $this->db->query($sql, [$supplierId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}purch_orders
                WHERE reference = ? ORDER BY ord_date DESC";
        $rows = $this->db->query($sql, [$reference]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOpen(): array
    {
        $sql = "SELECT po.* FROM {$this->prefix}purch_orders po
                WHERE po.total - po.alloc > 0.005
                ORDER BY po.ord_date";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): PurchaseOrder
    {
        return new PurchaseOrder(
            (int)$row['order_no'],
            (int)$row['supplier_id'],
            $row['comments'] ? (string)$row['comments'] : null,
            (string)$row['ord_date'],
            (string)$row['reference'],
            isset($row['requisition_no']) ? (string)$row['requisition_no'] : null,
            (string)$row['into_stock_location'],
            (string)$row['delivery_address'],
            (float)$row['total'],
            (float)$row['prep_amount'],
            (float)$row['alloc'],
            (int)$row['tax_included']
        );
    }
}
