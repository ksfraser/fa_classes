<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class BankTransaction
{
    private int $id;
    private int $type;
    private int $transNo;
    private string $bankAccount;
    private string $ref;
    private ?string $statementDate;
    private float $amount;
    private ?int $dimensionId;
    private ?int $dimension2Id;
    private ?string $personType;
    private ?int $personId;
    private ?string $tranDate;
    private bool $reconciled;

    public function __construct(
        int $id,
        int $type,
        int $transNo,
        string $bankAccount,
        string $ref,
        ?string $statementDate,
        float $amount,
        ?int $dimensionId = null,
        ?int $dimension2Id = null,
        ?string $personType = null,
        ?int $personId = null,
        ?string $tranDate = null,
        bool $reconciled = false
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->transNo = $transNo;
        $this->bankAccount = $bankAccount;
        $this->ref = $ref;
        $this->statementDate = $statementDate;
        $this->amount = $amount;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->personType = $personType;
        $this->personId = $personId;
        $this->tranDate = $tranDate;
        $this->reconciled = $reconciled;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getTransNo(): int { return $this->transNo; }
    public function getBankAccount(): string { return $this->bankAccount; }
    public function getRef(): string { return $this->ref; }
    public function getStatementDate(): ?string { return $this->statementDate; }
    public function getAmount(): float { return $this->amount; }
    public function getDimensionId(): ?int { return $this->dimensionId; }
    public function getDimension2Id(): ?int { return $this->dimension2Id; }
    public function getPersonType(): ?string { return $this->personType; }
    public function getPersonId(): ?int { return $this->personId; }
    public function getTranDate(): ?string { return $this->tranDate; }
    public function isReconciled(): bool { return $this->reconciled; }
}
