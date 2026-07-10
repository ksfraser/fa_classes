<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\ChartMasterRepository;
use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Repository\FiscalYearRepository;
use FrontAccounting\Service\Contracts\MiscService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of MiscService.
 *
 * Replaces FA core utility functions with DB queries where
 * applicable and no-op validations where not.
 *
 * ┌───────────────────────────────────────────────────────────┐
 * │                  MiscServiceStandard                       │
 * │  - fiscalYearRepo:     FiscalYearRepository               │
 * │  - exchangeRateRepo:   ExchangeRateRepository             │
 * │  - chartMasterRepo:    ChartMasterRepository              │
 * ├───────────────────────────────────────────────────────────┤
 * │  + checkNum($field, $min): bool — always true             │
 * │  + hasCurrencyRates($currency, $date, $allowFuture): bool │
 * │  + isDateInFiscalYear($date): bool                        │
 * │  + newDocDate($date): string                              │
 * │  + getGlAccount($accountCode): array|false                │
 * └───────────────────────────────────────────────────────────┘
 */
final class MiscServiceStandard implements MiscService
{
    private FiscalYearRepository $fiscalYearRepo;
    private ExchangeRateRepository $exchangeRateRepo;
    private ChartMasterRepository $chartMasterRepo;

    public function __construct(
        FiscalYearRepository $fiscalYearRepo,
        ExchangeRateRepository $exchangeRateRepo,
        ChartMasterRepository $chartMasterRepo
    ) {
        $this->fiscalYearRepo = $fiscalYearRepo;
        $this->exchangeRateRepo = $exchangeRateRepo;
        $this->chartMasterRepo = $chartMasterRepo;
    }

    public function checkNum(string $fieldName, float $minValue = 0): bool
    {
        return true;
    }

    public function hasCurrencyRates(string $currency, string $date, bool $allowFuture = false): bool
    {
        $rate = $this->exchangeRateRepo->findByDate($currency, $date);
        return $rate !== null;
    }

    public function isDateInFiscalYear(string $date): bool
    {
        $fy = $this->fiscalYearRepo->findForDate($date);
        return $fy !== null;
    }

    public function newDocDate(?string $date = null): string
    {
        return $date ?? date('Y-m-d');
    }

    /**
     * @return array<string, mixed>|false
     */
    public function getGlAccount(string $accountCode)
    {
        $row = $this->chartMasterRepo->findOneWhere(['account_code' => $accountCode]);
        return $row !== null ? $row : false;
    }
}
