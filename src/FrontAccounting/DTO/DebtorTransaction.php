<?php

namespace FrontAccounting\DTO;

final class DebtorTransaction
{
    /** @var int */
    private $transNo;
    /** @var int */
    private $type;
    /** @var int */
    private $debtorNo;
    /** @var int */
    private $branchCode;
    /** @var string */
    private $tranDate;
    /** @var string */
    private $dueDate;
    /** @var string */
    private $reference;
    /** @var int */
    private $order_;
    /** @var float */
    private $ovAmount;
    /** @var float */
    private $ovGst;
    /** @var float */
    private $ovFreight;
    /** @var float */
    private $ovFreightTax;
    /** @var float */
    private $ovDiscount;
    /** @var float */
    private $alloc;
    /** @var float */
    private $prepAmount;
    /** @var float */
    private $rate;
    /** @var int */
    private $shipVia;
    /** @var int */
    private $dimensionId;
    /** @var int */
    private $dimension2Id;
    /** @var int */
    private $paymentTerms;
    /** @var int */
    private $taxIncluded;

    public function __construct(
        int $transNo,
        int $type,
        int $debtorNo,
        int $branchCode,
        string $tranDate,
        string $dueDate,
        string $reference,
        int $order_,
        float $ovAmount,
        float $ovGst,
        float $ovFreight,
        float $ovFreightTax,
        float $ovDiscount,
        float $alloc,
        float $prepAmount,
        float $rate,
        int $shipVia,
        int $dimensionId,
        int $dimension2Id,
        int $paymentTerms,
        int $taxIncluded
    ) {
        $this->transNo = $transNo;
        $this->type = $type;
        $this->debtorNo = $debtorNo;
        $this->branchCode = $branchCode;
        $this->tranDate = $tranDate;
        $this->dueDate = $dueDate;
        $this->reference = $reference;
        $this->order_ = $order_;
        $this->ovAmount = $ovAmount;
        $this->ovGst = $ovGst;
        $this->ovFreight = $ovFreight;
        $this->ovFreightTax = $ovFreightTax;
        $this->ovDiscount = $ovDiscount;
        $this->alloc = $alloc;
        $this->prepAmount = $prepAmount;
        $this->rate = $rate;
        $this->shipVia = $shipVia;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->paymentTerms = $paymentTerms;
        $this->taxIncluded = $taxIncluded;
    }

    public function getTransNo(): int { return $this->transNo; }
    public function getType(): int { return $this->type; }
    public function getDebtorNo(): int { return $this->debtorNo; }
    public function getBranchCode(): int { return $this->branchCode; }
    public function getTranDate(): string { return $this->tranDate; }
    public function getDueDate(): string { return $this->dueDate; }
    public function getReference(): string { return $this->reference; }
    public function getOrder_(): int { return $this->order_; }
    public function getOvAmount(): float { return $this->ovAmount; }
    public function getOvGst(): float { return $this->ovGst; }
    public function getOvFreight(): float { return $this->ovFreight; }
    public function getOvFreightTax(): float { return $this->ovFreightTax; }
    public function getOvDiscount(): float { return $this->ovDiscount; }
    public function getAlloc(): float { return $this->alloc; }
    public function getPrepAmount(): float { return $this->prepAmount; }
    public function getRate(): float { return $this->rate; }
    public function getShipVia(): int { return $this->shipVia; }
    public function getDimensionId(): int { return $this->dimensionId; }
    public function getDimension2Id(): int { return $this->dimension2Id; }
    public function getPaymentTerms(): int { return $this->paymentTerms; }
    public function getTaxIncluded(): int { return $this->taxIncluded; }

    public function getTotal(): float
    {
        return $this->ovAmount + $this->ovGst + $this->ovFreight
            + $this->ovFreightTax + $this->ovDiscount;
    }

    public function getBalance(): float
    {
        return abs($this->getTotal()) - $this->alloc;
    }
}
