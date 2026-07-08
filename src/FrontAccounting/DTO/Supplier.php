<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Supplier
{
    private int $supplierId;
    private string $suppName;
    private string $suppRef;
    private string $address;
    private string $suppAddress;
    private string $gstNo;
    private string $contact;
    private string $suppAccountNo;
    private string $website;
    private string $bankAccount;
    private ?string $currCode;
    private ?int $paymentTerms;
    private int $taxIncluded;
    private int $dimensionId;
    private int $dimension2Id;
    private ?int $taxGroupId;
    private float $creditLimit;
    private string $purchaseAccount;
    private string $payableAccount;
    private string $paymentDiscountAccount;
    private string $notes;
    private int $inactive;

    public function __construct(
        int $supplierId,
        string $suppName,
        string $suppRef,
        string $address,
        string $suppAddress,
        string $gstNo,
        string $contact,
        string $suppAccountNo,
        string $website,
        string $bankAccount,
        ?string $currCode,
        ?int $paymentTerms,
        int $taxIncluded,
        int $dimensionId,
        int $dimension2Id,
        ?int $taxGroupId,
        float $creditLimit,
        string $purchaseAccount,
        string $payableAccount,
        string $paymentDiscountAccount,
        string $notes,
        int $inactive
    ) {
        $this->supplierId = $supplierId;
        $this->suppName = $suppName;
        $this->suppRef = $suppRef;
        $this->address = $address;
        $this->suppAddress = $suppAddress;
        $this->gstNo = $gstNo;
        $this->contact = $contact;
        $this->suppAccountNo = $suppAccountNo;
        $this->website = $website;
        $this->bankAccount = $bankAccount;
        $this->currCode = $currCode;
        $this->paymentTerms = $paymentTerms;
        $this->taxIncluded = $taxIncluded;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->taxGroupId = $taxGroupId;
        $this->creditLimit = $creditLimit;
        $this->purchaseAccount = $purchaseAccount;
        $this->payableAccount = $payableAccount;
        $this->paymentDiscountAccount = $paymentDiscountAccount;
        $this->notes = $notes;
        $this->inactive = $inactive;
    }

    public function getSupplierId(): int { return $this->supplierId; }
    public function getSuppName(): string { return $this->suppName; }
    public function getSuppRef(): string { return $this->suppRef; }
    public function getAddress(): string { return $this->address; }
    public function getSuppAddress(): string { return $this->suppAddress; }
    public function getGstNo(): string { return $this->gstNo; }
    public function getContact(): string { return $this->contact; }
    public function getSuppAccountNo(): string { return $this->suppAccountNo; }
    public function getWebsite(): string { return $this->website; }
    public function getBankAccount(): string { return $this->bankAccount; }
    public function getCurrCode(): ?string { return $this->currCode; }
    public function getPaymentTerms(): ?int { return $this->paymentTerms; }
    public function getTaxIncluded(): int { return $this->taxIncluded; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
    public function getTaxGroupId(): ?int { return $this->taxGroupId; }
    public function getCreditLimit(): float { return $this->creditLimit; }
    public function getPurchaseAccount(): string { return $this->purchaseAccount; }
    public function getPayableAccount(): string { return $this->payableAccount; }
    public function getPaymentDiscountAccount(): string { return $this->paymentDiscountAccount; }
    public function getNotes(): string { return $this->notes; }
    public function getInactive(): int { return $this->inactive; }
}
