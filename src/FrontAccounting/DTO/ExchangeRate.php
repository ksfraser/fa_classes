<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ExchangeRate
{
    private int $id;
    private string $currency;
    private float $rateBuy;
    private float $rateSell;
    private string $date;
    private ?string $dateTime;

    public function __construct(
        int $id,
        string $currency,
        float $rateBuy,
        float $rateSell,
        string $date,
        ?string $dateTime = null
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->rateBuy = $rateBuy;
        $this->rateSell = $rateSell;
        $this->date = $date;
        $this->dateTime = $dateTime;
    }

    public function getId(): int { return $this->id; }
    public function getCurrency(): string { return $this->currency; }
    public function getRateBuy(): float { return $this->rateBuy; }
    public function getRateSell(): float { return $this->rateSell; }
    public function getDate(): string { return $this->date; }
    public function getDateTime(): ?string { return $this->dateTime; }
}
