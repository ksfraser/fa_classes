<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesOrderDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesOrderDetailRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByOrder(int $orderNo, int $transType = 30): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_order_details
                WHERE order_no = ? AND trans_type = ?
                ORDER BY id";
        $rows = $this->db->query($sql, [$orderNo, $transType]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByStkCode(string $stkCode): array
    {
        $sql = "SELECT sod.*, so.debtor_no, so.reference
                FROM {$this->prefix}sales_order_details sod
                JOIN {$this->prefix}sales_orders so
                    ON so.order_no = sod.order_no AND so.trans_type = sod.trans_type
                WHERE sod.stk_code = ?
                  AND sod.quantity - sod.qty_sent > 0.005
                ORDER BY so.ord_date DESC";
        $rows = $this->db->query($sql, [$stkCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOpenByStkCode(string $stkCode): array
    {
        $sql = "SELECT sod.* FROM {$this->prefix}sales_order_details sod
                JOIN {$this->prefix}sales_orders so
                    ON so.order_no = sod.order_no AND so.trans_type = sod.trans_type
                WHERE sod.stk_code = ?
                  AND sod.quantity - sod.qty_sent > 0.005
                  AND so.total - so.alloc > 0.005
                ORDER BY sod.id";
        $rows = $this->db->query($sql, [$stkCode]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): SalesOrderDetail
    {
        return new SalesOrderDetail(
            (int)$row['id'],
            (int)$row['order_no'],
            (int)$row['trans_type'],
            (string)$row['stk_code'],
            isset($row['description']) ? (string)$row['description'] : null,
            (float)$row['qty_sent'],
            (float)$row['unit_price'],
            (float)$row['quantity'],
            (float)$row['invoiced'],
            (float)$row['discount_percent']
        );
    }

    protected function getTableName(): string
    {
        return 'sales_order_details';
    }
}
