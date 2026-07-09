<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PurchaseOrder;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PurchaseOrderRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'purch_orders';
    public function findById(int $orderNo): ?PurchaseOrder
    {
        return $this->findOne(['order_no' => $orderNo]);
    }

    public function findBySupplier(int $supplierId): array
    {
        return $this->find(['supplier_id' => $supplierId], ['ord_date' => 'DESC']);
    }

    public function findByReference(string $reference): array
    {
        return $this->find(['reference' => $reference], ['ord_date' => 'DESC']);
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

    protected function hydrate(array $row): PurchaseOrder
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
