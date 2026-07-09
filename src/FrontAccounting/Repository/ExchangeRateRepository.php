<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ExchangeRate;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ExchangeRateRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'exchange_rates';
    public function findById(int $id): ?ExchangeRate
    {
        $sql = "SELECT * FROM {$this->prefix}exchange_rates WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByCurrency(string $currency): array
    {
        $sql = "SELECT * FROM {$this->prefix}exchange_rates WHERE curr_code = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$currency]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findLatest(string $currency): ?ExchangeRate
    {
        $sql = "SELECT * FROM {$this->prefix}exchange_rates WHERE curr_code = ? ORDER BY date_ DESC LIMIT 1";
        $rows = $this->db->query($sql, [$currency]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByDate(string $currency, string $date): ?ExchangeRate
    {
        $sql = "SELECT * FROM {$this->prefix}exchange_rates WHERE curr_code = ? AND date_ = ? LIMIT 1";
        $rows = $this->db->query($sql, [$currency, $date]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    private function hydrate(array $row): ExchangeRate
    {
        return new ExchangeRate(
            (int)$row['id'],
            (string)$row['curr_code'],
            (float)$row['rate_buy'],
            (float)$row['rate_sell'],
            (string)$row['date_'],
            isset($row['date_time']) ? (string)$row['date_time'] : null
        );
    }

}
