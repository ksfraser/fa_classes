<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TransTaxDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TransTaxDetailRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByTransaction(int $transType, int $transNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}trans_tax_details WHERE trans_type = ? AND trans_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$transType, $transNo]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByTaxType(int $taxTypeId): array
    {
        $sql = "SELECT * FROM {$this->prefix}trans_tax_details WHERE tax_type_id = ? ORDER BY tran_date DESC";
        $rows = $this->db->query($sql, [$taxTypeId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}trans_tax_details WHERE tran_date >= ? AND tran_date <= ? ORDER BY tran_date, id";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): TransTaxDetail
    {
        return new TransTaxDetail(
            (int)$row['id'],
            (int)$row['trans_type'],
            (int)$row['trans_no'],
            isset($row['tran_date']) ? (string)$row['tran_date'] : null,
            (int)$row['tax_type_id'],
            (float)$row['rate'],
            (float)$row['exemption_percent'],
            (float)$row['amount'],
            (float)$row['net_amount']
        );
    }
}
