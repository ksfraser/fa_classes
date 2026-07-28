<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PurchaseOrderDetail
{
    /** @var int */
    private $poDetailItem;
    /** @var int */
    private $orderNo;
    /** @var string */
    private $itemCode;
    /** @var ?string */
    private $description;
    /** @var string */
    private $deliveryDate;
    /** @var float */
    private $qtyInvoiced;
    /** @var float */
    private $unitPrice;
    /** @var float */
    private $actPrice;
    /** @var float */
    private $stdCostUnit;
    /** @var float */
    private $quantityOrdered;
    /** @var float */
    private $quantityReceived;

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
