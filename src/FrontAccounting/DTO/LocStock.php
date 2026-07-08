<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class LocStock
{
    private string $locCode;
    private string $stockId;
    private float $quantity;
    private ?string $expiryDate;

    public function __construct(string $locCode, string $stockId, float $quantity, ?string $expiryDate = null)
    {
        $this->locCode = $locCode;
        $this->stockId = $stockId;
        $this->quantity = $quantity;
        $this->expiryDate = $expiryDate;
    }

    public function getLocCode(): string { return $this->locCode; }
    public function getStockId(): string { return $this->stockId; }
    public function getQuantity(): float { return $this->quantity; }
    public function getExpiryDate(): ?string { return $this->expiryDate; }
}
