<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ChartMaster
{
    private string $accountCode;
    private int $accountType;
    private string $accountName;
    private ?string $bankCode;
    private ?string $bankDescription;
    private bool $showInTrialBalance;
    private bool $inactive;

    public function __construct(
        string $accountCode,
        int $accountType,
        string $accountName,
        ?string $bankCode = null,
        ?string $bankDescription = null,
        bool $showInTrialBalance = true,
        bool $inactive = false
    ) {
        $this->accountCode = $accountCode;
        $this->accountType = $accountType;
        $this->accountName = $accountName;
        $this->bankCode = $bankCode;
        $this->bankDescription = $bankDescription;
        $this->showInTrialBalance = $showInTrialBalance;
        $this->inactive = $inactive;
    }

    public function getAccountCode(): string { return $this->accountCode; }
    public function getAccountType(): int { return $this->accountType; }
    public function getAccountName(): string { return $this->accountName; }
    public function getBankCode(): ?string { return $this->bankCode; }
    public function getBankDescription(): ?string { return $this->bankDescription; }
    public function getShowInTrialBalance(): bool { return $this->showInTrialBalance; }
    public function getInactive(): bool { return $this->inactive; }
}
