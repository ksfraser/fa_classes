<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Currency;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CurrencyRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'currencies';
    public function findByCode(string $currency): ?Currency
    {
        $sql = "SELECT * FROM {$this->prefix}currencies WHERE currency = ?";
        $rows = $this->db->query($sql, [$currency]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}currencies WHERE inactive = 0 ORDER BY currency";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}currencies ORDER BY currency";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Currency
    {
        return new Currency(
            (string)$row['currency'],
            (string)$row['curr_symbol'],
            (string)$row['currency_name'],
            isset($row['decimal_places']) ? (int)$row['decimal_places'] : 2,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
