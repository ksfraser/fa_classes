<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class GlTrans
{
    private int $counter;
    private int $type;
    private int $typeNo;
    private ?string $tranDate;
    private string $account;
    private string $memo;
    private float $amount;
    private int $dimensionId;
    private int $dimension2Id;
    private ?int $personTypeId;
    private ?int $personId;

    public function __construct(
        int $counter,
        int $type,
        int $typeNo,
        ?string $tranDate,
        string $account,
        string $memo,
        float $amount,
        int $dimensionId = 0,
        int $dimension2Id = 0,
        ?int $personTypeId = null,
        ?int $personId = null
    ) {
        $this->counter = $counter;
        $this->type = $type;
        $this->typeNo = $typeNo;
        $this->tranDate = $tranDate;
        $this->account = $account;
        $this->memo = $memo;
        $this->amount = $amount;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->personTypeId = $personTypeId;
        $this->personId = $personId;
    }

    public function getCounter(): int { return $this->counter; }
    public function getType(): int { return $this->type; }
    public function getTypeNo(): int { return $this->typeNo; }
    public function getTranDate(): ?string { return $this->tranDate; }
    public function getAccount(): string { return $this->account; }
    public function getMemo(): string { return $this->memo; }
    public function getAmount(): float { return $this->amount; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
    public function getPersonTypeId(): ?int { return $this->personTypeId; }
    public function getPersonId(): ?int { return $this->personId; }
}
