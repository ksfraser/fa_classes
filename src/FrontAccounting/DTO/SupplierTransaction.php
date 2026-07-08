<?php

namespace FrontAccounting\DTO;

final class SupplierTransaction
{
    /** @var int */
    private $transNo;
    /** @var int */
    private $type;
    /** @var int */
    private $supplierId;
    /** @var string */
    private $reference;
    /** @var string */
    private $suppReference;
    /** @var string */
    private $tranDate;
    /** @var string */
    private $dueDate;
    /** @var float */
    private $ovAmount;
    /** @var float */
    private $ovDiscount;
    /** @var float */
    private $ovGst;
    /** @var float */
    private $rate;
    /** @var float */
    private $alloc;
    /** @var int */
    private $taxIncluded;

    public function __construct(
        int $transNo,
        int $type,
        int $supplierId,
        string $reference,
        string $suppReference,
        string $tranDate,
        string $dueDate,
        float $ovAmount,
        float $ovDiscount,
        float $ovGst,
        float $rate,
        float $alloc,
        int $taxIncluded
    ) {
        $this->transNo = $transNo;
        $this->type = $type;
        $this->supplierId = $supplierId;
        $this->reference = $reference;
        $this->suppReference = $suppReference;
        $this->tranDate = $tranDate;
        $this->dueDate = $dueDate;
        $this->ovAmount = $ovAmount;
        $this->ovDiscount = $ovDiscount;
        $this->ovGst = $ovGst;
        $this->rate = $rate;
        $this->alloc = $alloc;
        $this->taxIncluded = $taxIncluded;
    }

    public function getTransNo(): int { return $this->transNo; }
    public function getType(): int { return $this->type; }
    public function getSupplierId(): int { return $this->supplierId; }
    public function getReference(): string { return $this->reference; }
    public function getSuppReference(): string { return $this->suppReference; }
    public function getTranDate(): string { return $this->tranDate; }
    public function getDueDate(): string { return $this->dueDate; }
    public function getOvAmount(): float { return $this->ovAmount; }
    public function getOvDiscount(): float { return $this->ovDiscount; }
    public function getOvGst(): float { return $this->ovGst; }
    public function getRate(): float { return $this->rate; }
    public function getAlloc(): float { return $this->alloc; }
    public function getTaxIncluded(): int { return $this->taxIncluded; }

    public function getTotal(): float
    {
        return $this->ovAmount + $this->ovGst + $this->ovDiscount;
    }

    public function getBalance(): float
    {
        return abs($this->getTotal()) - $this->alloc;
    }
}
