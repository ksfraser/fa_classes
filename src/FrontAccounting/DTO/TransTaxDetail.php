<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TransTaxDetail
{
    /** @var int */
    private $id;
    /** @var int */
    private $transType;
    /** @var int */
    private $transNo;
    /** @var ?string */
    private $tranDate;
    /** @var int */
    private $taxTypeId;
    /** @var float */
    private $rate;
    /** @var float */
    private $exemptionPercent;
    /** @var float */
    private $amount;
    /** @var float */
    private $netAmount;

    public function __construct(
        int $id,
        int $transType,
        int $transNo,
        ?string $tranDate,
        int $taxTypeId,
        float $rate,
        float $exemptionPercent,
        float $amount,
        float $netAmount
    ) {
        $this->id = $id;
        $this->transType = $transType;
        $this->transNo = $transNo;
        $this->tranDate = $tranDate;
        $this->taxTypeId = $taxTypeId;
        $this->rate = $rate;
        $this->exemptionPercent = $exemptionPercent;
        $this->amount = $amount;
        $this->netAmount = $netAmount;
    }

    public function getId(): int { return $this->id; }
    public function getTransType(): int { return $this->transType; }
    public function getTransNo(): int { return $this->transNo; }
    public function getTranDate(): ?string { return $this->tranDate; }
    public function getTaxTypeId(): int { return $this->taxTypeId; }
    public function getRate(): float { return $this->rate; }
    public function getExemptionPercent(): float { return $this->exemptionPercent; }
    public function getAmount(): float { return $this->amount; }
    public function getNetAmount(): float { return $this->netAmount; }
}
