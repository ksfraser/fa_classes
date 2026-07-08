<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PurchaseOrderDetail
{
    private int $poDetailItem;
    private int $orderNo;
    private string $itemCode;
    private ?string $description;
    private string $deliveryDate;
    private float $qtyInvoiced;
    private float $unitPrice;
    private float $actPrice;
    private float $stdCostUnit;
    private float $quantityOrdered;
    private float $quantityReceived;

    public function __construct(
        int $poDetailItem,
        int $orderNo,
        string $itemCode,
        ?string $description,
        string $deliveryDate,
        float $qtyInvoiced,
        float $unitPrice,
        float $actPrice,
        float $stdCostUnit,
        float $quantityOrdered,
        float $quantityReceived
    ) {
        $this->poDetailItem = $poDetailItem;
        $this->orderNo = $orderNo;
        $this->itemCode = $itemCode;
        $this->description = $description;
        $this->deliveryDate = $deliveryDate;
        $this->qtyInvoiced = $qtyInvoiced;
        $this->unitPrice = $unitPrice;
        $this->actPrice = $actPrice;
        $this->stdCostUnit = $stdCostUnit;
        $this->quantityOrdered = $quantityOrdered;
        $this->quantityReceived = $quantityReceived;
    }

    public function getPoDetailItem(): int { return $this->poDetailItem; }
    public function getOrderNo(): int { return $this->orderNo; }
    public function getItemCode(): string { return $this->itemCode; }
    public function getDescription(): ?string { return $this->description; }
    public function getDeliveryDate(): string { return $this->deliveryDate; }
    public function getQtyInvoiced(): float { return $this->qtyInvoiced; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getActPrice(): float { return $this->actPrice; }
    public function getStdCostUnit(): float { return $this->stdCostUnit; }
    public function getQuantityOrdered(): float { return $this->quantityOrdered; }
    public function getQuantityReceived(): float { return $this->quantityReceived; }
}
