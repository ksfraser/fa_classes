<?php

namespace FrontAccounting\DTO;

final class PurchOrderDetail
{
    /** @var int */
    private $poDetailItem;
    /** @var int */
    private $orderNo;
    /** @var string */
    private $itemCode;
    /** @var float */
    private $quantityOrdered;
    /** @var float */
    private $quantityReceived;
    /** @var float */
    private $qtyInvoiced;

    public function __construct(
        int $poDetailItem,
        int $orderNo,
        string $itemCode,
        float $quantityOrdered,
        float $quantityReceived,
        float $qtyInvoiced
    ) {
        $this->poDetailItem = $poDetailItem;
        $this->orderNo = $orderNo;
        $this->itemCode = $itemCode;
        $this->quantityOrdered = $quantityOrdered;
        $this->quantityReceived = $quantityReceived;
        $this->qtyInvoiced = $qtyInvoiced;
    }

    public function getPoDetailItem(): int { return $this->poDetailItem; }
    public function getOrderNo(): int { return $this->orderNo; }
    public function getItemCode(): string { return $this->itemCode; }
    public function getQuantityOrdered(): float { return $this->quantityOrdered; }
    public function getQuantityReceived(): float { return $this->quantityReceived; }
    public function getQtyInvoiced(): float { return $this->qtyInvoiced; }
}
