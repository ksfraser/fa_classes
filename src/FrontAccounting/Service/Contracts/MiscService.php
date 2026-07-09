<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for miscellaneous FA utility operations.
 */
interface MiscService
{
    public function checkNum(string $fieldName, float $minValue = 0): bool;

    public function hasCurrencyRates(string $currency, string $date, bool $allowFuture = false): bool;

    public function isDateInFiscalYear(string $date): bool;

    public function newDocDate(?string $date = null): string;

    /**
     * @return array<string, mixed>|false
     */
    public function getGlAccount(string $accountCode);
}
