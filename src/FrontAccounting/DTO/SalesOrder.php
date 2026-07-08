<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SalesOrder
{
    private int $orderNo;
    private int $transType;
    private int $version;
    private int $type;
    private int $debtorNo;
    private int $branchCode;
    private string $reference;
    private string $customerRef;
    private ?string $comments;
    private string $ordDate;
    private int $orderType;
    private int $shipVia;
    private string $deliveryAddress;
    private ?string $contactPhone;
    private ?string $contactEmail;
    private string $deliverTo;
    private float $freightCost;
    private string $fromStkLoc;
    private string $deliveryDate;
    private ?int $paymentTerms;
    private float $total;
    private float $prepAmount;
    private float $alloc;

    public function __construct(
        int $orderNo,
        int $transType,
        int $version,
        int $type,
        int $debtorNo,
        int $branchCode,
        string $reference,
        string $customerRef,
        ?string $comments,
        string $ordDate,
        int $orderType,
        int $shipVia,
        string $deliveryAddress,
        ?string $contactPhone,
        ?string $contactEmail,
        string $deliverTo,
        float $freightCost,
        string $fromStkLoc,
        string $deliveryDate,
        ?int $paymentTerms,
        float $total,
        float $prepAmount,
        float $alloc
    ) {
        $this->orderNo = $orderNo;
        $this->transType = $transType;
        $this->version = $version;
        $this->type = $type;
        $this->debtorNo = $debtorNo;
        $this->branchCode = $branchCode;
        $this->reference = $reference;
        $this->customerRef = $customerRef;
        $this->comments = $comments;
        $this->ordDate = $ordDate;
        $this->orderType = $orderType;
        $this->shipVia = $shipVia;
        $this->deliveryAddress = $deliveryAddress;
        $this->contactPhone = $contactPhone;
        $this->contactEmail = $contactEmail;
        $this->deliverTo = $deliverTo;
        $this->freightCost = $freightCost;
        $this->fromStkLoc = $fromStkLoc;
        $this->deliveryDate = $deliveryDate;
        $this->paymentTerms = $paymentTerms;
        $this->total = $total;
        $this->prepAmount = $prepAmount;
        $this->alloc = $alloc;
    }

    public function getOrderNo(): int { return $this->orderNo; }
    public function getTransType(): int { return $this->transType; }
    public function getVersion(): int { return $this->version; }
    public function getType(): int { return $this->type; }
    public function getDebtorNo(): int { return $this->debtorNo; }
    public function getBranchCode(): int { return $this->branchCode; }
    public function getReference(): string { return $this->reference; }
    public function getCustomerRef(): string { return $this->customerRef; }
    public function getComments(): ?string { return $this->comments; }
    public function getOrdDate(): string { return $this->ordDate; }
    public function getOrderType(): int { return $this->orderType; }
    public function getShipVia(): int { return $this->shipVia; }
    public function getDeliveryAddress(): string { return $this->deliveryAddress; }
    public function getContactPhone(): ?string { return $this->contactPhone; }
    public function getContactEmail(): ?string { return $this->contactEmail; }
    public function getDeliverTo(): string { return $this->deliverTo; }
    public function getFreightCost(): float { return $this->freightCost; }
    public function getFromStkLoc(): string { return $this->fromStkLoc; }
    public function getDeliveryDate(): string { return $this->deliveryDate; }
    public function getPaymentTerms(): ?int { return $this->paymentTerms; }
    public function getTotal(): float { return $this->total; }
    public function getPrepAmount(): float { return $this->prepAmount; }
    public function getAlloc(): float { return $this->alloc; }
}
