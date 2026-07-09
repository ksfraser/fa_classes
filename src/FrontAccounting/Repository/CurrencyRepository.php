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
        return $this->findOne(['currency' => $currency]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['currency' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['currency' => 'ASC']);
    }

    protected function hydrate(array $row): Currency
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
