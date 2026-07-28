<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WoRequirement
{
    /** @var int */
    private $id;
    /** @var int */
    private $workOrderId;
    /** @var string */
    private $stockId;
    /** @var float */
    private $qtyRequired;
    /** @var float */
    private $qtyIssued;
    /** @var float */
    private $qtyLost;
    /** @var ?string */
    private $date_;

    public function __construct(
        int $id,
        int $workOrderId,
        string $stockId,
        float $qtyRequired,
        float $qtyIssued = 0.0,
        float $qtyLost = 0.0,
        ?string $date_ = null
    ) {
        $this->id = $id;
        $this->workOrderId = $workOrderId;
        $this->stockId = $stockId;
        $this->qtyRequired = $qtyRequired;
        $this->qtyIssued = $qtyIssued;
        $this->qtyLost = $qtyLost;
        $this->date_ = $date_;
    }

    public function getId(): int { return $this->id; }
    public function getWorkOrderId(): int { return $this->workOrderId; }
    public function getStockId(): string { return $this->stockId; }
    public function getQtyRequired(): float { return $this->qtyRequired; }
    public function getQtyIssued(): float { return $this->qtyIssued; }
    public function getQtyLost(): float { return $this->qtyLost; }
    public function getDate(): ?string { return $this->date_; }
}
