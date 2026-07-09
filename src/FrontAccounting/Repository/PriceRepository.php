<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Price;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PriceRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'prices';
    public function findById(int $id): ?Price
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByStockId(string $stockId): array
    {
        return $this->find(['stock_id' => $stockId], ['sales_type_id' => 'ASC']);
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
        return $this->find(['inactive' => 0], ['stock_id' => 'ASC']);
    }

    protected function hydrate(array $row): Price
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

}
