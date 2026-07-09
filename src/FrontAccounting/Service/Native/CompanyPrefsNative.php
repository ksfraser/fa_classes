<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * Native wrapper for FA core company preference functions.
 *
 * Wraps get_company_prefs() and get_company_pref() from
 * includes/sysprefs.inc.
 */
class CompanyPrefsNative
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
