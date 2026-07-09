<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesPos;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesPosRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sales_pos';
    public function findById(int $id): ?SalesPos
    {
        $sql = "SELECT * FROM {$this->prefix}sales_pos WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByName(string $posName): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_pos WHERE pos_name LIKE ? ORDER BY pos_name";
        $rows = $this->db->query($sql, ['%' . $posName . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_pos WHERE inactive = 0 ORDER BY pos_name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}sales_pos ORDER BY pos_name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): SalesPos
    {
        return new SalesPos(
            (int)$row['id'],
            (string)$row['pos_name'],
            (int)($row['cash_account'] ?? 0),
            (int)($row['credit_card_account'] ?? 0),
            (int)($row['exchange_diff_account'] ?? 0),
            (int)($row['discount_account'] ?? 0),
            (int)($row['default_sales_type'] ?? 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
