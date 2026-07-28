<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WorkCentre
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var ?float */
    private $overheadCost;
    /** @var ?float */
    private $labourCost;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $id,
        string $name,
        string $description,
        ?float $overheadCost = null,
        ?float $labourCost = null,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->overheadCost = $overheadCost;
        $this->labourCost = $labourCost;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getOverheadCost(): ?float { return $this->overheadCost; }
    public function getLabourCost(): ?float { return $this->labourCost; }
    public function getInactive(): bool { return $this->inactive; }
}
