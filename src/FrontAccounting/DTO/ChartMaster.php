<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ChartMaster
{
    /** @var string */
    private $accountCode;
    /** @var int */
    private $accountType;
    /** @var string */
    private $accountName;
    /** @var ?string */
    private $bankCode;
    /** @var ?string */
    private $bankDescription;
    /** @var bool */
    private $showInTrialBalance;
    /** @var bool */
    private $inactive;

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
