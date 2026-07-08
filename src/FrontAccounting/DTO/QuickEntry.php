<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class QuickEntry
{
    private int $id;
    private string $description;
    private int $type;
    private int $baseAmount;
    private string $baseAmountType;
    private string $baseDesc;

    public function __construct(int $id, string $description, int $type, int $baseAmount, string $baseAmountType = '', string $baseDesc = '')
    {
        $this->id = $id;
        $this->description = $description;
        $this->type = $type;
        $this->baseAmount = $baseAmount;
        $this->baseAmountType = $baseAmountType;
        $this->baseDesc = $baseDesc;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getType(): int { return $this->type; }
    public function getBaseAmount(): int { return $this->baseAmount; }
    public function getBaseAmountType(): string { return $this->baseAmountType; }
    public function getBaseDesc(): string { return $this->baseDesc; }
}
