<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ItemCode
{
    private int $id;
    private string $itemCode;
    private string $stockId;
    private ?string $description;
    private ?string $categoryId;
    private float $quantity;
    private bool $isForeign;
    private bool $inactive;

    public function __construct(
        int $id,
        string $itemCode,
        string $stockId,
        ?string $description = null,
        ?string $categoryId = null,
        float $quantity = 1.0,
        bool $isForeign = false,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->itemCode = $itemCode;
        $this->stockId = $stockId;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->quantity = $quantity;
        $this->isForeign = $isForeign;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getItemCode(): string { return $this->itemCode; }
    public function getStockId(): string { return $this->stockId; }
    public function getDescription(): ?string { return $this->description; }
    public function getCategoryId(): ?string { return $this->categoryId; }
    public function getQuantity(): float { return $this->quantity; }
    public function isForeign(): bool { return $this->isForeign; }
    public function getInactive(): bool { return $this->inactive; }
}
