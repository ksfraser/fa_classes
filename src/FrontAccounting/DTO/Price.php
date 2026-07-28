<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Price
{
    /** @var int */
    private $id;
    /** @var string */
    private $stockId;
    /** @var int */
    private $salesTypeId;
    /** @var string */
    private $currency;
    /** @var float */
    private $price;
    /** @var ?string */
    private $priceListDescription;
    /** @var ?string */
    private $startDate;
    /** @var ?string */
    private $endDate;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $id,
        string $stockId,
        int $salesTypeId,
        string $currency,
        float $price,
        ?string $priceListDescription = null,
        ?string $startDate = null,
        ?string $endDate = null,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->stockId = $stockId;
        $this->salesTypeId = $salesTypeId;
        $this->currency = $currency;
        $this->price = $price;
        $this->priceListDescription = $priceListDescription;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getStockId(): string { return $this->stockId; }
    public function getSalesTypeId(): int { return $this->salesTypeId; }
    public function getCurrency(): string { return $this->currency; }
    public function getPrice(): float { return $this->price; }
    public function getPriceListDescription(): ?string { return $this->priceListDescription; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function getInactive(): bool { return $this->inactive; }
}
