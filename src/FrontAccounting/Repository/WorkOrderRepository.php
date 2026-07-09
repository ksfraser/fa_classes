<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WorkOrder;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WorkOrderRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'workorders';
    public function findById(int $id): ?WorkOrder
    {
        $sql = "SELECT * FROM {$this->prefix}workorders WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}workorders WHERE stock_id = ? ORDER BY id DESC";
        $rows = $this->db->query($sql, [$stockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}workorders WHERE reference LIKE ? ORDER BY id DESC";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findReleased(): array
    {
        $sql = "SELECT * FROM {$this->prefix}workorders WHERE released = 1 ORDER BY id DESC";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByWorkCentre(int $workCentreId): array
    {
        $sql = "SELECT * FROM {$this->prefix}workorders WHERE workcentre_id = ? ORDER BY id DESC";
        $rows = $this->db->query($sql, [$workCentreId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WorkOrder
    {
        return new WorkOrder(
            (int)$row['id'],
            (string)$row['stock_id'],
            (string)$row['reference'],
            (int)$row['type'],
            isset($row['required_by']) ? (string)$row['required_by'] : null,
            isset($row['date_']) ? (string)$row['date_'] : null,
            (string)($row['units_issued'] ?? '0'),
            (string)($row['units_required'] ?? '0'),
            (string)($row['units_manufactured'] ?? '0'),
            (int)($row['workcentre_id'] ?? 0),
            (float)($row['unit_cost'] ?? 0.0),
            (float)($row['labour_cost'] ?? 0.0),
            (float)($row['overhead_cost'] ?? 0.0),
            (int)($row['released'] ?? 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
