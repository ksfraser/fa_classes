<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SalesOrderDetail
{
    private int $id;
    private int $orderNo;
    private int $transType;
    private string $stkCode;
    private ?string $description;
    private float $qtySent;
    private float $unitPrice;
    private float $quantity;
    private float $invoiced;
    private float $discountPercent;

    public function __construct(
        int $id,
        int $orderNo,
        int $transType,
        string $stkCode,
        ?string $description,
        float $qtySent,
        float $unitPrice,
        float $quantity,
        float $invoiced,
        float $discountPercent
    ) {
        $this->id = $id;
        $this->orderNo = $orderNo;
        $this->transType = $transType;
        $this->stkCode = $stkCode;
        $this->description = $description;
        $this->qtySent = $qtySent;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->invoiced = $invoiced;
        $this->discountPercent = $discountPercent;
    }

    public function getId(): int { return $this->id; }
    public function getOrderNo(): int { return $this->orderNo; }
    public function getTransType(): int { return $this->transType; }
    public function getStkCode(): string { return $this->stkCode; }
    public function getDescription(): ?string { return $this->description; }
    public function getQtySent(): float { return $this->qtySent; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getQuantity(): float { return $this->quantity; }
    public function getInvoiced(): float { return $this->invoiced; }
    public function getDiscountPercent(): float { return $this->discountPercent; }
}
