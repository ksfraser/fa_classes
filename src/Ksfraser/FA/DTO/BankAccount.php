<?php

namespace Ksfraser\FA\DTO;

final class BankAccount
{
    /** @var int */
    private $id;
    /** @var string */
    private $bankAccountName;
    /** @var string */
    private $bankAccountNumber;
    /** @var string */
    private $bankCurrCode;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $id,
        string $bankAccountName,
        string $bankAccountNumber,
        string $bankCurrCode,
        bool $inactive
    ) {
        $this->id = $id;
        $this->bankAccountName = $bankAccountName;
        $this->bankAccountNumber = $bankAccountNumber;
        $this->bankCurrCode = $bankCurrCode;
        $this->inactive = $inactive;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBankAccountName(): string
    {
        return $this->bankAccountName;
    }

    public function getBankAccountNumber(): string
    {
        return $this->bankAccountNumber;
    }

    public function getBankCurrCode(): string
    {
        return $this->bankCurrCode;
    }

    public function isInactive(): bool
    {
        return $this->inactive;
    }
}
