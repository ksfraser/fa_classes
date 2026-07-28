<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ItemUnit
{
    /** @var string */
    private $abbreviation;
    /** @var string */
    private $name;
    /** @var int */
    private $decimals;
    /** @var bool */
    private $inactive;

    public function __construct(string $abbreviation, string $name, int $decimals = 0, bool $inactive = false)
    {
        $this->abbreviation = $abbreviation;
        $this->name = $name;
        $this->decimals = $decimals;
        $this->inactive = $inactive;
    }

    public function getAbbreviation(): string { return $this->abbreviation; }
    public function getName(): string { return $this->name; }
    public function getDecimals(): int { return $this->decimals; }
    public function getInactive(): bool { return $this->inactive; }
}
