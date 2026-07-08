<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoRequirement;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoRequirementRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?WoRequirement
    {
        $sql = "SELECT * FROM {$this->prefix}wo_requirements WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByWorkOrder(int $workOrderId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_requirements WHERE workorder_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$workOrderId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_requirements WHERE stock_id = ? ORDER BY workorder_id";
        $rows = $this->db->query($sql, [$stockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WoRequirement
    {
        return new WoRequirement(
            (int)$row['id'],
            (int)$row['workorder_id'],
            (string)$row['stock_id'],
            (float)$row['qty_required'],
            (float)($row['qty_issued'] ?? 0.0),
            (float)($row['qty_lost'] ?? 0.0),
            isset($row['date_']) ? (string)$row['date_'] : null
        );
    }

    protected function getTableName(): string
    {
        return 'wo_requirements';
    }
}
