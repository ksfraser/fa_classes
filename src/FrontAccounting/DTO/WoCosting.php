<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WoCosting
{
    private int $id;
    private int $workOrderId;
    private int $crType;
    private int $crNo;
    private string $stockId;
    private float $qty;
    private float $cost;
    private string $date_;

    public function __construct(int $id, int $workOrderId, int $crType, int $crNo, string $stockId, float $qty, float $cost, string $date_ = '')
    {
        $this->id = $id;
        $this->workOrderId = $workOrderId;
        $this->crType = $crType;
        $this->crNo = $crNo;
        $this->stockId = $stockId;
        $this->qty = $qty;
        $this->cost = $cost;
        $this->date_ = $date_;
    }

    public function getId(): int { return $this->id; }
    public function getWorkOrderId(): int { return $this->workOrderId; }
    public function getCrType(): int { return $this->crType; }
    public function getCrNo(): int { return $this->crNo; }
    public function getStockId(): string { return $this->stockId; }
    public function getQty(): float { return $this->qty; }
    public function getCost(): float { return $this->cost; }
    public function getDate(): string { return $this->date_; }
}
