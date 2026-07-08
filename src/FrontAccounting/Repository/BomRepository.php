<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Bom;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class BomRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?Bom
    {
        $sql = "SELECT * FROM {$this->prefix}bom WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByParent(string $parentStockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}bom WHERE parent = ? ORDER BY id";
        $rows = $this->db->query($sql, [$parentStockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByComponent(string $componentStockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}bom WHERE component = ? ORDER BY parent";
        $rows = $this->db->query($sql, [$componentStockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}bom WHERE inactive = 0 ORDER BY parent";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): Bom
    {
        return new Bom(
            (int)$row['id'],
            (string)$row['parent'],
            (string)$row['component'],
            (float)$row['quantity'],
            (float)($row['labour_cost'] ?? 0.0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'bom';
    }
}
