<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ItemTaxType
{
    private int $id;
    private string $name;
    private string $longName;
    private bool $inactive;

    public function __construct(int $id, string $name, string $longName = '', bool $inactive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->longName = $longName;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getLongName(): string { return $this->longName; }
    public function getInactive(): bool { return $this->inactive; }
}
