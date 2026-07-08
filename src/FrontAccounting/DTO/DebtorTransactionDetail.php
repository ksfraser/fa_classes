<?php

namespace FrontAccounting\DTO;

final class DebtorTransactionDetail
{
    /** @var int */
    private $id;
    /** @var int */
    private $debtorTransNo;
    /** @var int */
    private $debtorTransType;
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
    /** @var float */
    private $discountPercent;
    /** @var float */
    private $standardCost;
    /** @var float */
    private $qtyDone;
    /** @var int */
    private $srcId;

    public function __construct(
        int $id,
        int $debtorTransNo,
        int $debtorTransType,
        string $stockId,
        string $description,
        float $unitPrice,
        float $unitTax,
        float $quantity,
        float $discountPercent,
        float $standardCost,
        float $qtyDone,
        int $srcId
    ) {
        $this->id = $id;
        $this->debtorTransNo = $debtorTransNo;
        $this->debtorTransType = $debtorTransType;
        $this->stockId = $stockId;
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        $this->unitTax = $unitTax;
        $this->quantity = $quantity;
        $this->discountPercent = $discountPercent;
        $this->standardCost = $standardCost;
        $this->qtyDone = $qtyDone;
        $this->srcId = $srcId;
    }

    public function getId(): int { return $this->id; }
    public function getDebtorTransNo(): int { return $this->debtorTransNo; }
    public function getDebtorTransType(): int { return $this->debtorTransType; }
    public function getStockId(): string { return $this->stockId; }
    public function getDescription(): string { return $this->description; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getUnitTax(): float { return $this->unitTax; }
    public function getQuantity(): float { return $this->quantity; }
    public function getDiscountPercent(): float { return $this->discountPercent; }
    public function getStandardCost(): float { return $this->standardCost; }
    public function getQtyDone(): float { return $this->qtyDone; }
    public function getSrcId(): int { return $this->srcId; }
}
