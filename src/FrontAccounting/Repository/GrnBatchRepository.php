<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\GrnBatch;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GrnBatchRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'grn_batch';
    public function findById(int $id): ?GrnBatch
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByPurchOrder(int $purchOrderNo): array
    {
        return $this->find(['purch_order_no' => $purchOrderNo], ['id' => 'ASC']);
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE reference LIKE ? ORDER BY id DESC";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByLocation(string $location): array
    {
        return $this->find(['loc_code' => $location], ['id' => 'DESC']);
    }

    public function findReceived(): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE is_received = 1 ORDER BY id DESC";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    protected function hydrate(array $row): GrnBatch
    {
        return new GrnBatch(
            (int)$row['id'],
            (int)$row['purch_order_no'],
            isset($row['reference']) ? (string)$row['reference'] : null,
            isset($row['ord_date']) ? (string)$row['ord_date'] : null,
            isset($row['delivery_date']) ? (string)$row['delivery_date'] : null,
            isset($row['due_date']) ? (string)$row['due_date'] : null,
            isset($row['loc_code']) ? (string)$row['loc_code'] : '',
            (bool)(isset($row['is_received']) ? (int)$row['is_received'] : 0),
            (bool)(isset($row['is_partial']) ? (int)$row['is_partial'] : 0)
        );
    }

}
