<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Service\Contracts\ExchangeRateService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of ExchangeRateService.
 *
 * Queries exchange_rates directly instead of FA core's
 * get_exchange_rate_from_to().
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │              ExchangeRateServiceStandard                  │
 * │  - exchangeRateRepo: ExchangeRateRepository               │
 * ├──────────────────────────────────────────────────────────┤
 * │  + getExchangeRateFromTo($from, $to, $date): float       │
 * └──────────────────────────────────────────────────────────┘
 */
final class ExchangeRateServiceStandard implements ExchangeRateService
{
    private ExchangeRateRepository $exchangeRateRepo;

    public function __construct(ExchangeRateRepository $exchangeRateRepo)
    {
        $this->exchangeRateRepo = $exchangeRateRepo;
    }

    public function getExchangeRateFromTo(string $from, string $to, string $date): float
    {
        if ($from === $to) {
            return 1.0;
        }

        if ($from !== 'USD') {
            $rate = $this->exchangeRateRepo->findByDate($from, $date);
            if ($rate !== null) {
                return $rate->getRateBuy();
            }
        }

        if ($to !== 'USD') {
            $rate = $this->exchangeRateRepo->findByDate($to, $date);
            if ($rate !== null) {
                return 1.0 / $rate->getRateBuy();
            }
        }

        return 1.0;
    }
}
