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
        return $this->findOne(['id' => $id]);
    }

    public function findByCurrency(string $currency): array
    {
        return $this->find(['curr_code' => $currency], ['date_' => 'DESC']);
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

    protected function hydrate(array $row): ExchangeRate
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
