<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Area
{
    private int $areaCode;
    private string $description;
    private bool $inactive;

    public function __construct(int $areaCode, string $description, bool $inactive = false)
    {
        $this->areaCode = $areaCode;
        $this->description = $description;
        $this->inactive = $inactive;
    }

    public function getAreaCode(): int { return $this->areaCode; }
    public function getDescription(): string { return $this->description; }
    public function getInactive(): bool { return $this->inactive; }
}
