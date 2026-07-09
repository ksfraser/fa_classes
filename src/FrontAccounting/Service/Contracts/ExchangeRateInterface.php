<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for exchange rate operations.
 */
interface ExchangeRateInterface
{
    public function getExchangeRateFromTo(string $from, string $to, string $date): float;
}
