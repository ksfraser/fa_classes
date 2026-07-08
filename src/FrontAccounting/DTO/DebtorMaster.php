<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class DebtorMaster
{
    private int $debtorNo;
    private string $name;
    private string $debtorRef;
    private ?string $address;
    private string $taxId;
    private string $currCode;
    private int $salesType;
    private int $dimensionId;
    private int $dimension2Id;
    private int $creditStatus;
    private ?int $paymentTerms;
    private float $discount;
    private float $pymtDiscount;
    private float $creditLimit;
    private string $notes;
    private int $inactive;

    public function __construct(
        int $debtorNo,
        string $name,
        string $debtorRef,
        ?string $address,
        string $taxId,
        string $currCode,
        int $salesType,
        int $dimensionId,
        int $dimension2Id,
        int $creditStatus,
        ?int $paymentTerms,
        float $discount,
        float $pymtDiscount,
        float $creditLimit,
        string $notes,
        int $inactive
    ) {
        $this->debtorNo = $debtorNo;
        $this->name = $name;
        $this->debtorRef = $debtorRef;
        $this->address = $address;
        $this->taxId = $taxId;
        $this->currCode = $currCode;
        $this->salesType = $salesType;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->creditStatus = $creditStatus;
        $this->paymentTerms = $paymentTerms;
        $this->discount = $discount;
        $this->pymtDiscount = $pymtDiscount;
        $this->creditLimit = $creditLimit;
        $this->notes = $notes;
        $this->inactive = $inactive;
    }

    public function getDebtorNo(): int { return $this->debtorNo; }
    public function getName(): string { return $this->name; }
    public function getDebtorRef(): string { return $this->debtorRef; }
    public function getAddress(): ?string { return $this->address; }
    public function getTaxId(): string { return $this->taxId; }
    public function getCurrCode(): string { return $this->currCode; }
    public function getSalesType(): int { return $this->salesType; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
    public function getCreditStatus(): int { return $this->creditStatus; }
    public function getPaymentTerms(): ?int { return $this->paymentTerms; }
    public function getDiscount(): float { return $this->discount; }
    public function getPymtDiscount(): float { return $this->pymtDiscount; }
    public function getCreditLimit(): float { return $this->creditLimit; }
    public function getNotes(): string { return $this->notes; }
    public function getInactive(): int { return $this->inactive; }
}
