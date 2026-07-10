<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\SysPrefsRepository;
use FrontAccounting\Service\Contracts\CompanyPrefsService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of CompanyPrefsService.
 *
 * Queries sys_prefs table directly instead of FA core's
 * get_company_prefs() / get_company_pref().
 *
 * ┌────────────────────────────────────────────────────────────┐
 * │               CompanyPrefsServiceStandard                   │
 * │  - sysPrefsRepo: SysPrefsRepository                        │
 * ├────────────────────────────────────────────────────────────┤
 * │  + getCompanyPrefs(): array                                │
 * │  + getCompanyPref($name): string                           │
 * └────────────────────────────────────────────────────────────┘
 */
final class CompanyPrefsServiceStandard implements CompanyPrefsService
{
    private SysPrefsRepository $sysPrefsRepo;

    public function __construct(SysPrefsRepository $sysPrefsRepo)
    {
        $this->sysPrefsRepo = $sysPrefsRepo;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCompanyPrefs(): array
    {
        $rows = $this->sysPrefsRepo->findGlobal();
        $result = [];
        foreach ($rows as $pref) {
            $result[$pref->getName()] = $pref->getValue();
        }
        return $result;
    }

    public function getCompanyPref(string $name): string
    {
        $pref = $this->sysPrefsRepo->findByName($name);
        return $pref !== null ? $pref->getValue() : '';
    }
}
