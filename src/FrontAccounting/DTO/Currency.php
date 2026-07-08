<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Currency
{
    private string $currency;
    private string $currencySymbol;
    private string $currencyName;
    private int $decimalPlaces;
    private bool $inactive;

    public function __construct(
        string $currency,
        string $currencySymbol,
        string $currencyName,
        int $decimalPlaces = 2,
        bool $inactive = false
    ) {
        $this->currency = $currency;
        $this->currencySymbol = $currencySymbol;
        $this->currencyName = $currencyName;
        $this->decimalPlaces = $decimalPlaces;
        $this->inactive = $inactive;
    }

    public function getCurrency(): string { return $this->currency; }
    public function getCurrencySymbol(): string { return $this->currencySymbol; }
    public function getCurrencyName(): string { return $this->currencyName; }
    public function getDecimalPlaces(): int { return $this->decimalPlaces; }
    public function getInactive(): bool { return $this->inactive; }
}
