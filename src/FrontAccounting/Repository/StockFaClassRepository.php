<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\StockFaClass;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class StockFaClassRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?StockFaClass
    {
        $sql = "SELECT * FROM {$this->prefix}stock_fa_class WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_fa_class ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_fa_class WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): StockFaClass
    {
        return new StockFaClass(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['description'],
            isset($row['depreciation_rate']) ? (float)$row['depreciation_rate'] : null,
            (string)($row['fa_account_code'] ?? ''),
            (string)($row['depreciation_account_code'] ?? ''),
            (string)($row['accum_depreciation_account_code'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'stock_fa_class';
    }
}
