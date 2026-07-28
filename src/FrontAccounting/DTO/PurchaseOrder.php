<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PurchaseOrder
{
    /** @var int */
    private $orderNo;
    /** @var int */
    private $supplierId;
    /** @var ?string */
    private $comments;
    /** @var string */
    private $ordDate;
    /** @var string */
    private $reference;
    /** @var ?string */
    private $requisitionNo;
    /** @var string */
    private $intoStockLocation;
    /** @var string */
    private $deliveryAddress;
    /** @var float */
    private $total;
    /** @var float */
    private $prepAmount;
    /** @var float */
    private $alloc;
    /** @var int */
    private $taxIncluded;

    public function __construct(
        int $orderNo,
        int $supplierId,
        ?string $comments,
        string $ordDate,
        string $reference,
        ?string $requisitionNo,
        string $intoStockLocation,
        string $deliveryAddress,
        float $total,
        float $prepAmount,
        float $alloc,
        int $taxIncluded
    ) {
        $this->orderNo = $orderNo;
        $this->supplierId = $supplierId;
        $this->comments = $comments;
        $this->ordDate = $ordDate;
        $this->reference = $reference;
        $this->requisitionNo = $requisitionNo;
        $this->intoStockLocation = $intoStockLocation;
        $this->deliveryAddress = $deliveryAddress;
        $this->total = $total;
        $this->prepAmount = $prepAmount;
        $this->alloc = $alloc;
        $this->taxIncluded = $taxIncluded;
    }

    public function getOrderNo(): int { return $this->orderNo; }
    public function getSupplierId(): int { return $this->supplierId; }
    public function getComments(): ?string { return $this->comments; }
    public function getOrdDate(): string { return $this->ordDate; }
    public function getReference(): string { return $this->reference; }
    public function getRequisitionNo(): ?string { return $this->requisitionNo; }
    public function getIntoStockLocation(): string { return $this->intoStockLocation; }
    public function getDeliveryAddress(): string { return $this->deliveryAddress; }
    public function getTotal(): float { return $this->total; }
    public function getPrepAmount(): float { return $this->prepAmount; }
    public function getAlloc(): float { return $this->alloc; }
    public function getTaxIncluded(): int { return $this->taxIncluded; }
}
