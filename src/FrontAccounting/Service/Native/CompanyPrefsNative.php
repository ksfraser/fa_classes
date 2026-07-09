<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\CompanyPrefsInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core company preference functions.
 */
class CompanyPrefsNative implements CompanyPrefsInterface
{
    /**
     * Wrap get_company_prefs() — returns all company preferences.
     *
     * @return array<string, mixed>
     */
    public function getCompanyPrefs(): array
    {
        return \get_company_prefs();
    }

    /**
     * Wrap get_company_pref() — returns a single preference value.
     */
    public function getCompanyPref(string $name): string
    {
        return \get_company_pref($name);
    }
}
