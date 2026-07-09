<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for company preference operations.
 */
interface CompanyPrefsService
{
    /**
     * @return array<string, mixed>
     */
    public function getCompanyPrefs(): array;

    public function getCompanyPref(string $name): string;
}
