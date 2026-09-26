<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class BudgetTrans
{
    /** @var int */
    private $id;
    /** @var int */
    private $counter;
    /** @var string */
    private $account;
    /** @var string */
    private $tranDate;
    /** @var int */
    private $dimensionId;
    /** @var int */
    private $dimension2Id;
    /** @var float */
    private $amount;

    public function __construct(
        int $id,
        int $counter,
        string $account,
        string $tranDate,
        int $dimensionId,
        int $dimension2Id,
        float $amount
    ) {
        $this->id = $id;
        $this->counter = $counter;
        $this->account = $account;
        $this->tranDate = $tranDate;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->amount = $amount;
    }

    public function getId(): int { return $this->id; }
    public function getCounter(): int { return $this->counter; }
    public function getAccount(): string { return $this->account; }
    public function getTranDate(): string { return $this->tranDate; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
    public function getAmount(): float { return $this->amount; }
}
