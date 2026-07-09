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
        return $this->findOne(['id' => $id]);
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
        return $this->find(['inactive' => 0], ['pos_name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['pos_name' => 'ASC']);
    }

    protected function hydrate(array $row): SalesPos
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
