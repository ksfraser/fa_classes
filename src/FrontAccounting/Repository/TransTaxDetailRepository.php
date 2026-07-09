<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TransTaxDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TransTaxDetailRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'trans_tax_details';
    public function findByTransaction(int $transType, int $transNo): array
    {
        return $this->find(['trans_type' => $transType, 'trans_no' => $transNo], ['id' => 'ASC']);
    }

    public function findByTaxType(int $taxTypeId): array
    {
        return $this->find(['tax_type_id' => $taxTypeId], ['tran_date' => 'DESC']);
    }

    public function findByDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}trans_tax_details WHERE tran_date >= ? AND tran_date <= ? ORDER BY tran_date, id";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): TransTaxDetail
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
