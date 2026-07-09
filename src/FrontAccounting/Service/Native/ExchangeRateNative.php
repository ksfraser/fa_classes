<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * @since 2026-07-09
 * Native wrapper for FA core exchange rate functions.
 *
 * Wraps get_exchange_rate_from_to() from includes/exchange_rate.inc.
 */
class ExchangeRateNative
{
    /**
     * Wrap get_exchange_rate_from_to().
     */
    public function getExchangeRateFromTo(string $from, string $to, string $date): float
    {
        return \get_exchange_rate_from_to($from, $to, $date);
    }
}
