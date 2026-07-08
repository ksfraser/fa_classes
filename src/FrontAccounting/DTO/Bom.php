<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Bom
{
    private int $id;
    private string $parent;
    private string $component;
    private float $quantity;
    private float $labourCost;
    private bool $inactive;

    public function __construct(
        int $id,
        string $parent,
        string $component,
        float $quantity,
        float $labourCost = 0.0,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->parent = $parent;
        $this->component = $component;
        $this->quantity = $quantity;
        $this->labourCost = $labourCost;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getParent(): string { return $this->parent; }
    public function getComponent(): string { return $this->component; }
    public function getQuantity(): float { return $this->quantity; }
    public function getLabourCost(): float { return $this->labourCost; }
    public function getInactive(): bool { return $this->inactive; }
}
