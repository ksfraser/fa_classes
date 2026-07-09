<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesOrder;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesOrderRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sales_orders';
    public function findById(int $orderNo, int $transType = 30): ?SalesOrder
    {
        $sql = "SELECT * FROM {$this->prefix}sales_orders
                WHERE order_no = ? AND trans_type = ?";
        $rows = $this->db->query($sql, [$orderNo, $transType]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByDebtor(int $debtorNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_orders
                WHERE debtor_no = ? AND trans_type = 30
                ORDER BY ord_date DESC
                LIMIT 50";
        $rows = $this->db->query($sql, [$debtorNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_orders
                WHERE reference = ?
                ORDER BY ord_date DESC";
        $rows = $this->db->query($sql, [$reference]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findOpen(): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_orders
                WHERE trans_type = 30 AND total - alloc > 0.005
                ORDER BY ord_date";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): SalesOrder
    {
        return new SalesOrder(
            (int)$row['order_no'],
            (int)$row['trans_type'],
            (int)$row['version'],
            (int)$row['type'],
            (int)$row['debtor_no'],
            (int)$row['branch_code'],
            (string)$row['reference'],
            (string)$row['customer_ref'],
            isset($row['comments']) ? (string)$row['comments'] : null,
            (string)$row['ord_date'],
            (int)$row['order_type'],
            (int)$row['ship_via'],
            (string)$row['delivery_address'],
            isset($row['contact_phone']) ? (string)$row['contact_phone'] : null,
            isset($row['contact_email']) ? (string)$row['contact_email'] : null,
            (string)$row['deliver_to'],
            (float)$row['freight_cost'],
            (string)$row['from_stk_loc'],
            (string)$row['delivery_date'],
            isset($row['payment_terms']) ? (int)$row['payment_terms'] : null,
            (float)$row['total'],
            (float)$row['prep_amount'],
            (float)$row['alloc']
        );
    }

}
