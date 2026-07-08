<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class StockFaClass
{
    private int $id;
    private string $name;
    private string $description;
    private ?float $depreciationRate;
    private string $faAccountCode;
    private string $depreciationAccountCode;
    private string $accumDepreciationAccountCode;
    private bool $inactive;

    public function __construct(
        int $id,
        string $name,
        string $description,
        ?float $depreciationRate = null,
        string $faAccountCode = '',
        string $depreciationAccountCode = '',
        string $accumDepreciationAccountCode = '',
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->depreciationRate = $depreciationRate;
        $this->faAccountCode = $faAccountCode;
        $this->depreciationAccountCode = $depreciationAccountCode;
        $this->accumDepreciationAccountCode = $accumDepreciationAccountCode;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getDepreciationRate(): ?float { return $this->depreciationRate; }
    public function getFaAccountCode(): string { return $this->faAccountCode; }
    public function getDepreciationAccountCode(): string { return $this->depreciationAccountCode; }
    public function getAccumDepreciationAccountCode(): string { return $this->accumDepreciationAccountCode; }
    public function getInactive(): bool { return $this->inactive; }
}
