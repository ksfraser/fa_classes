<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SupplierInvoiceItem;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SupplierInvoiceItemRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'supp_invoice_items';

    public function findByTransaction(int $type, int $transNo): array
    {
        $sql = "SELECT inv.*, grn.*, unit_price AS FullUnitPrice,
                       stock.units,
                       tax_type.exempt,
                       tax_type.name AS tax_type_name
                FROM {$this->prefix}supp_invoice_items inv
                LEFT JOIN {$this->prefix}grn_items grn ON grn.id = inv.grn_item_id
                LEFT JOIN {$this->prefix}stock_master stock ON stock.stock_id = inv.stock_id
                LEFT JOIN {$this->prefix}item_tax_types tax_type ON stock.tax_type_id = tax_type.id
                WHERE supp_trans_type = ? AND supp_trans_no = ?
                ORDER BY inv.id";
        $rows = $this->db->query($sql, [$type, $transNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByGrnItem(int $grnItemId): array
    {
        return $this->find(['grn_item_id' => $grnItemId], ['supp_trans_no' => 'ASC']);
    }

    public function findByPoDetailItem(int $poDetailItemId): array
    {
        return $this->find(['po_detail_item_id' => $poDetailItemId], ['supp_trans_no' => 'ASC']);
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}supp_invoice_items
                SET quantity = 0, unit_price = 0
                WHERE supp_trans_type = ? AND supp_trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    protected function hydrate(array $row): SupplierInvoiceItem
    {
        return new SupplierInvoiceItem(
            (int)$row['id'],
            (int)$row['supp_trans_type'],
            (int)$row['supp_trans_no'],
            (string)$row['stock_id'],
            (string)$row['description'],
            (float)$row['unit_price'],
            (float)$row['unit_tax'],
            (float)$row['quantity'],
            (int)$row['grn_item_id'],
            (int)$row['po_detail_item_id'],
            (string)$row['memo_'],
            (int)$row['dimension_id'],
            (int)$row['dimension2_id']
        );
    }

}
