<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SalesType
{
    private int $id;
    private string $salesType;
    private float $taxIncluded;
    private float $factor;
    private bool $inactive;

    public function __construct(int $id, string $salesType, float $taxIncluded = 0.0, float $factor = 1.0, bool $inactive = false)
    {
        $this->id = $id;
        $this->salesType = $salesType;
        $this->taxIncluded = $taxIncluded;
        $this->factor = $factor;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getSalesType(): string { return $this->salesType; }
    public function getTaxIncluded(): float { return $this->taxIncluded; }
    public function getFactor(): float { return $this->factor; }
    public function getInactive(): bool { return $this->inactive; }
}
