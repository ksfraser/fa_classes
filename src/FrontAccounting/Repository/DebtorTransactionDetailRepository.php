<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\DebtorTransactionDetail;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class DebtorTransactionDetailRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'debtor_trans_details';

    public function findByTransaction(int $type, int $transNo): array
    {
        $sql = "SELECT line.*, item.units, item.mb_flag
                FROM {$this->prefix}debtor_trans_details line
                LEFT JOIN {$this->prefix}stock_master item ON item.stock_id = line.stock_id
                WHERE debtor_trans_type = ? AND debtor_trans_no = ?
                ORDER BY id";
        $rows = $this->db->query($sql, [$type, $transNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findBySrcId(int $srcId): array
    {
        $sql = "SELECT line.*, item.units, item.mb_flag
                FROM {$this->prefix}debtor_trans_details line
                LEFT JOIN {$this->prefix}stock_master item ON item.stock_id = line.stock_id
                WHERE src_id = ?
                ORDER BY id";
        $rows = $this->db->query($sql, [$srcId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function void(int $type, int $transNo): int
    {
        $sql = "UPDATE {$this->prefix}debtor_trans_details
                SET quantity = 0, unit_price = 0, unit_tax = 0,
                    discount_percent = 0, standard_cost = 0, src_id = 0
                WHERE debtor_trans_type = ? AND debtor_trans_no = ?";
        return $this->db->execute($sql, [$type, $transNo]);
    }

    protected function hydrate(array $row): DebtorTransactionDetail
    {
        return new DebtorTransactionDetail(
            (int)$row['id'],
            (int)$row['debtor_trans_no'],
            (int)$row['debtor_trans_type'],
            (string)$row['stock_id'],
            (string)$row['description'],
            (float)$row['unit_price'],
            (float)$row['unit_tax'],
            (float)$row['quantity'],
            (float)$row['discount_percent'],
            (float)$row['standard_cost'],
            (float)$row['qty_done'],
            (int)$row['src_id']
        );
    }

}
