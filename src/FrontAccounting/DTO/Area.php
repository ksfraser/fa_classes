<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Area
{
    /** @var int */
    private $areaCode;
    /** @var string */
    private $description;
    /** @var bool */
    private $inactive;

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
