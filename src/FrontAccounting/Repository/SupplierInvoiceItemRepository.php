<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SupplierInvoiceItem;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SupplierInvoiceItemRepository {
    use RepositoryTrait;
    /** @var DbAdapterInterface */
    private $db;
    /** @var string */
    private $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

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
        $sql = "SELECT * FROM {$this->prefix}supp_invoice_items
                WHERE grn_item_id = ?
                ORDER BY supp_trans_no";
        $rows = $this->db->query($sql, [$grnItemId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByPoDetailItem(int $poDetailItemId): array
    {
        $sql = "SELECT * FROM {$this->prefix}supp_invoice_items
                WHERE po_detail_item_id = ?
                ORDER BY supp_trans_no";
        $rows = $this->db->query($sql, [$poDetailItemId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}supp_invoice_items
                SET quantity = 0, unit_price = 0
                WHERE supp_trans_type = ? AND supp_trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    private function hydrate(array $row): SupplierInvoiceItem
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

    protected function getTableName(): string
    {
        return 'supp_invoice_items';
    }
}
