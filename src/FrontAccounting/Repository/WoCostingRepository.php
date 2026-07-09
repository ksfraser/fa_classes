<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoCosting;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoCostingRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'wo_costing';
    public function findById(int $id): ?WoCosting
    {
        $sql = "SELECT * FROM {$this->prefix}wo_costing WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_costing WHERE workorder_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$workOrderId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findBySource(int $crType, int $crNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_costing WHERE cr_type = ? AND cr_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$crType, $crNo]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WoCosting
    {
        return new WoCosting(
            (int)$row['id'],
            (int)$row['workorder_id'],
            (int)$row['cr_type'],
            (int)$row['cr_no'],
            (string)$row['stock_id'],
            (float)$row['qty'],
            (float)$row['cost'],
            (string)($row['date_'] ?? '')
        );
    }

}
