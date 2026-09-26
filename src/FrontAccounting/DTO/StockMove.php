<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class StockMove
{
    /** @var int */
    private $transId;
    /** @var int */
    private $transNo;
    /** @var string */
    private $stockId;
    /** @var int */
    private $type;
    /** @var string */
    private $locCode;
    /** @var string */
    private $tranDate;
    /** @var float */
    private $price;
    /** @var string */
    private $reference;
    /** @var float */
    private $qty;
    /** @var float */
    private $standardCost;

    public function __construct(
        int $transId,
        int $transNo,
        string $stockId,
        int $type,
        string $locCode,
        string $tranDate,
        float $price,
        string $reference,
        float $qty,
        float $standardCost
    ) {
        $this->transId = $transId;
        $this->transNo = $transNo;
        $this->stockId = $stockId;
        $this->type = $type;
        $this->locCode = $locCode;
        $this->tranDate = $tranDate;
        $this->price = $price;
        $this->reference = $reference;
        $this->qty = $qty;
        $this->standardCost = $standardCost;
    }

    public function getTransId(): int { return $this->transId; }
    public function getTransNo(): int { return $this->transNo; }
    public function getStockId(): string { return $this->stockId; }
    public function getType(): int { return $this->type; }
    public function getLocCode(): string { return $this->locCode; }
    public function getTranDate(): string { return $this->tranDate; }
    public function getPrice(): float { return $this->price; }
    public function getReference(): string { return $this->reference; }
    public function getQty(): float { return $this->qty; }
    public function getStandardCost(): float { return $this->standardCost; }
}
