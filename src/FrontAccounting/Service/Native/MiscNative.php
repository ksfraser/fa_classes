<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * Native wrapper for misc FA core utility functions.
 *
 * Wraps check_num(), db_has_currency_rates(), is_date_in_fiscalyear(),
 * new_doc_date(), and get_gl_account().
 */
class MiscNative
{
    /**
     * Wrap check_num() — validates a numeric input field.
     */
    public function checkNum(string $fieldName, float $minValue = 0): bool
    {
        return \check_num($fieldName, $minValue);
    }

    /**
     * Wrap db_has_currency_rates().
     */
    public function hasCurrencyRates(string $currency, string $date, bool $allowFuture = false): bool
    {
        return \db_has_currency_rates($currency, $date, $allowFuture);
    }

    /**
     * Wrap is_date_in_fiscalyear().
     */
    public function isDateInFiscalYear(string $date): bool
    {
        return \is_date_in_fiscalyear($date);
    }

    /**
     * Wrap new_doc_date().
     */
    public function newDocDate(?string $date = null): string
    {
        return \new_doc_date($date);
    }

    /**
     * Wrap get_gl_account().
     *
     * @return array<string, mixed>|false
     */
    public function getGlAccount(string $accountCode)
    {
        return \get_gl_account($accountCode);
    }
}
