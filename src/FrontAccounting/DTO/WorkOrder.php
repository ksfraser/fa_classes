<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WorkOrder
{
    private int $id;
    private string $stockId;
    private string $reference;
    private int $type;
    private ?string $requiredBy;
    private ?string $date_;
    private string $unitsIssued;
    private string $unitsRequired;
    private string $unitsManufactured;
    private int $workCentreId;
    private float $unitCost;
    private float $labourCost;
    private float $overheadCost;
    private int $released;
    private bool $inactive;

    public function __construct(
        int $id,
        string $stockId,
        string $reference,
        int $type,
        ?string $requiredBy,
        ?string $date_ = null,
        string $unitsIssued = '0',
        string $unitsRequired = '0',
        string $unitsManufactured = '0',
        int $workCentreId = 0,
        float $unitCost = 0.0,
        float $labourCost = 0.0,
        float $overheadCost = 0.0,
        int $released = 0,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->stockId = $stockId;
        $this->reference = $reference;
        $this->type = $type;
        $this->requiredBy = $requiredBy;
        $this->date_ = $date_;
        $this->unitsIssued = $unitsIssued;
        $this->unitsRequired = $unitsRequired;
        $this->unitsManufactured = $unitsManufactured;
        $this->workCentreId = $workCentreId;
        $this->unitCost = $unitCost;
        $this->labourCost = $labourCost;
        $this->overheadCost = $overheadCost;
        $this->released = $released;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getStockId(): string { return $this->stockId; }
    public function getReference(): string { return $this->reference; }
    public function getType(): int { return $this->type; }
    public function getRequiredBy(): ?string { return $this->requiredBy; }
    public function getDate(): ?string { return $this->date_; }
    public function getUnitsIssued(): string { return $this->unitsIssued; }
    public function getUnitsRequired(): string { return $this->unitsRequired; }
    public function getUnitsManufactured(): string { return $this->unitsManufactured; }
    public function getWorkCentreId(): int { return $this->workCentreId; }
    public function getUnitCost(): float { return $this->unitCost; }
    public function getLabourCost(): float { return $this->labourCost; }
    public function getOverheadCost(): float { return $this->overheadCost; }
    public function getReleased(): int { return $this->released; }
    public function getInactive(): bool { return $this->inactive; }
}
