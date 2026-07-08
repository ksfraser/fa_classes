<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PurchData
{
    private int $id;
    private int $supplierId;
    private string $stockId;
    private float $price;
    private float $suppliersUom;
    private string $conversionFactor;
    private string $supplierDescription;
    private bool $inactive;

    public function __construct(
        int $id,
        int $supplierId,
        string $stockId,
        float $price,
        float $suppliersUom = 1.0,
        string $conversionFactor = '1',
        string $supplierDescription = '',
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->supplierId = $supplierId;
        $this->stockId = $stockId;
        $this->price = $price;
        $this->suppliersUom = $suppliersUom;
        $this->conversionFactor = $conversionFactor;
        $this->supplierDescription = $supplierDescription;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getSupplierId(): int { return $this->supplierId; }
    public function getStockId(): string { return $this->stockId; }
    public function getPrice(): float { return $this->price; }
    public function getSuppliersUom(): float { return $this->suppliersUom; }
    public function getConversionFactor(): string { return $this->conversionFactor; }
    public function getSupplierDescription(): string { return $this->supplierDescription; }
    public function getInactive(): bool { return $this->inactive; }
}
