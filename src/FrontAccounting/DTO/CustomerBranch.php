<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CustomerBranch
{
    private int $branchCode;
    private int $debtorNo;
    private string $brName;
    private string $branchRef;
    private string $brAddress;
    private ?int $area;
    private int $salesman;
    private string $defaultLocation;
    private ?int $taxGroupId;
    private string $salesAccount;
    private string $salesDiscountAccount;
    private string $receivablesAccount;
    private string $paymentDiscountAccount;
    private int $defaultShipVia;
    private string $brPostAddress;
    private int $groupNo;
    private string $notes;
    private ?string $bankAccount;
    private int $inactive;

    public function __construct(
        int $branchCode,
        int $debtorNo,
        string $brName,
        string $branchRef,
        string $brAddress,
        ?int $area,
        int $salesman,
        string $defaultLocation,
        ?int $taxGroupId,
        string $salesAccount,
        string $salesDiscountAccount,
        string $receivablesAccount,
        string $paymentDiscountAccount,
        int $defaultShipVia,
        string $brPostAddress,
        int $groupNo,
        string $notes,
        ?string $bankAccount,
        int $inactive
    ) {
        $this->branchCode = $branchCode;
        $this->debtorNo = $debtorNo;
        $this->brName = $brName;
        $this->branchRef = $branchRef;
        $this->brAddress = $brAddress;
        $this->area = $area;
        $this->salesman = $salesman;
        $this->defaultLocation = $defaultLocation;
        $this->taxGroupId = $taxGroupId;
        $this->salesAccount = $salesAccount;
        $this->salesDiscountAccount = $salesDiscountAccount;
        $this->receivablesAccount = $receivablesAccount;
        $this->paymentDiscountAccount = $paymentDiscountAccount;
        $this->defaultShipVia = $defaultShipVia;
        $this->brPostAddress = $brPostAddress;
        $this->groupNo = $groupNo;
        $this->notes = $notes;
        $this->bankAccount = $bankAccount;
        $this->inactive = $inactive;
    }

    public function getBranchCode(): int { return $this->branchCode; }
    public function getDebtorNo(): int { return $this->debtorNo; }
    public function getBrName(): string { return $this->brName; }
    public function getBranchRef(): string { return $this->branchRef; }
    public function getBrAddress(): string { return $this->brAddress; }
    public function getArea(): ?int { return $this->area; }
    public function getSalesman(): int { return $this->salesman; }
    public function getDefaultLocation(): string { return $this->defaultLocation; }
    public function getTaxGroupId(): ?int { return $this->taxGroupId; }
    public function getSalesAccount(): string { return $this->salesAccount; }
    public function getSalesDiscountAccount(): string { return $this->salesDiscountAccount; }
    public function getReceivablesAccount(): string { return $this->receivablesAccount; }
    public function getPaymentDiscountAccount(): string { return $this->paymentDiscountAccount; }
    public function getDefaultShipVia(): int { return $this->defaultShipVia; }
    public function getBrPostAddress(): string { return $this->brPostAddress; }
    public function getGroupNo(): int { return $this->groupNo; }
    public function getNotes(): string { return $this->notes; }
    public function getBankAccount(): ?string { return $this->bankAccount; }
    public function getInactive(): int { return $this->inactive; }
}
