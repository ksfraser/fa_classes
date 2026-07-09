<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\ExchangeRateService;

/**
 * @since 2026-07-09
 * Native implementation of ExchangeRateService wrapping FA core rate functions.
 */
class ExchangeRateServiceNative implements ExchangeRateService
{
    /**
     * Wrap get_exchange_rate_from_to().
     */
    public function getExchangeRateFromTo(string $from, string $to, string $date): float
    {
        return \get_exchange_rate_from_to($from, $to, $date);
    }
}
