<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Price;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PriceRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?Price
    {
        $sql = "SELECT * FROM {$this->prefix}prices WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}prices WHERE stock_id = ? ORDER BY sales_type_id";
        $rows = $this->db->query($sql, [$stockId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findBySalesType(int $salesTypeId): array
    {
        $sql = "SELECT * FROM {$this->prefix}prices WHERE sales_type_id = ? AND inactive = 0 ORDER BY stock_id";
        $rows = $this->db->query($sql, [$salesTypeId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findPrice(string $stockId, int $salesTypeId, string $currency): ?Price
    {
        $sql = "SELECT * FROM {$this->prefix}prices WHERE stock_id = ? AND sales_type_id = ? AND curr_abrev = ? AND inactive = 0 LIMIT 1";
        $rows = $this->db->query($sql, [$stockId, $salesTypeId, $currency]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}prices WHERE inactive = 0 ORDER BY stock_id";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Price
    {
        return new Price(
            (int)$row['id'],
            (string)$row['stock_id'],
            (int)$row['sales_type_id'],
            (string)$row['curr_abrev'],
            (float)$row['price'],
            isset($row['price_list_description']) ? (string)$row['price_list_description'] : null,
            isset($row['start_date']) ? (string)$row['start_date'] : null,
            isset($row['end_date']) ? (string)$row['end_date'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'prices';
    }
}
