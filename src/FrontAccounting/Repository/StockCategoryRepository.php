<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\StockCategory;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class StockCategoryRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $categoryId): ?StockCategory
    {
        $sql = "SELECT * FROM {$this->prefix}stock_category WHERE category_id = ?";
        $rows = $this->db->query($sql, [$categoryId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByDescription(string $description): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_category WHERE description LIKE ? ORDER BY description";
        $rows = $this->db->query($sql, ['%' . $description . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_category WHERE inactive = 0 ORDER BY description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}stock_category ORDER BY description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): StockCategory
    {
        return new StockCategory(
            (int)$row['category_id'],
            (string)$row['description'],
            isset($row['long_description']) ? (string)$row['long_description'] : null,
            isset($row['dflt_tax_type']) ? (int)$row['dflt_tax_type'] : 0,
            isset($row['dflt_units']) ? (int)$row['dflt_units'] : 0,
            isset($row['dflt_mb_flag']) ? (int)$row['dflt_mb_flag'] : 0,
            isset($row['dflt_sales_account']) ? (string)$row['dflt_sales_account'] : null,
            isset($row['dflt_inventory_account']) ? (string)$row['dflt_inventory_account'] : null,
            isset($row['dflt_cogs_account']) ? (string)$row['dflt_cogs_account'] : null,
            isset($row['dflt_adjustment_account']) ? (string)$row['dflt_adjustment_account'] : null,
            isset($row['dflt_assembly_account']) ? (string)$row['dflt_assembly_account'] : null,
            isset($row['dflt_dim_account']) ? (string)$row['dflt_dim_account'] : null,
            isset($row['dflt_wip_account']) ? (string)$row['dflt_wip_account'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'stock_category';
    }
}
