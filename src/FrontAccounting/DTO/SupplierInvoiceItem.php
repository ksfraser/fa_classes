<?php

namespace FrontAccounting\DTO;

final class SupplierInvoiceItem
{
    /** @var int */
    private $id;
    /** @var int */
    private $suppTransType;
    /** @var int */
    private $suppTransNo;
    /** @var string */
    private $stockId;
    /** @var string */
    private $description;
    /** @var float */
    private $unitPrice;
    /** @var float */
    private $unitTax;
    /** @var float */
    private $quantity;
    /** @var int */
    private $grnItemId;
    /** @var int */
    private $poDetailItemId;
    /** @var string */
    private $memo;
    /** @var int */
    private $dimensionId;
    /** @var int */
    private $dimension2Id;

    public function __construct(
        int $id,
        int $suppTransType,
        int $suppTransNo,
        string $stockId,
        string $description,
        float $unitPrice,
        float $unitTax,
        float $quantity,
        int $grnItemId,
        int $poDetailItemId,
        string $memo = '',
        int $dimensionId = 0,
        int $dimension2Id = 0
    ) {
        $this->id = $id;
        $this->suppTransType = $suppTransType;
        $this->suppTransNo = $suppTransNo;
        $this->stockId = $stockId;
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        $this->unitTax = $unitTax;
        $this->quantity = $quantity;
        $this->grnItemId = $grnItemId;
        $this->poDetailItemId = $poDetailItemId;
        $this->memo = $memo;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
    }

    public function getId(): int { return $this->id; }
    public function getSuppTransType(): int { return $this->suppTransType; }
    public function getSuppTransNo(): int { return $this->suppTransNo; }
    public function getStockId(): string { return $this->stockId; }
    public function getDescription(): string { return $this->description; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getUnitTax(): float { return $this->unitTax; }
    public function getQuantity(): float { return $this->quantity; }
    public function getGrnItemId(): int { return $this->grnItemId; }
    public function getPoDetailItemId(): int { return $this->poDetailItemId; }
    public function getMemo(): string { return $this->memo; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
}
