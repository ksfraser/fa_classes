<?php

namespace FrontAccounting\DTO;

final class SupplierAllocation
{
    /** @var float */
    private $amount;
    /** @var int */
    private $transTypeFrom;
    /** @var int */
    private $transNoFrom;
    /** @var int */
    private $transTypeTo;
    /** @var int */
    private $transNoTo;
    /** @var int */
    private $personId;
    /** @var string Y-m-d */
    private $dateAlloc;

    public function __construct(
        float $amount,
        int $transTypeFrom,
        int $transNoFrom,
        int $transTypeTo,
        int $transNoTo,
        int $personId,
        string $dateAlloc
    ) {
        $this->amount = $amount;
        $this->transTypeFrom = $transTypeFrom;
        $this->transNoFrom = $transNoFrom;
        $this->transTypeTo = $transTypeTo;
        $this->transNoTo = $transNoTo;
        $this->personId = $personId;
        $this->dateAlloc = $dateAlloc;
    }

    public function getAmount(): float { return $this->amount; }
    public function getTransTypeFrom(): int { return $this->transTypeFrom; }
    public function getTransNoFrom(): int { return $this->transNoFrom; }
    public function getTransTypeTo(): int { return $this->transTypeTo; }
    public function getTransNoTo(): int { return $this->transNoTo; }
    public function getPersonId(): int { return $this->personId; }
    public function getDateAlloc(): string { return $this->dateAlloc; }
}
