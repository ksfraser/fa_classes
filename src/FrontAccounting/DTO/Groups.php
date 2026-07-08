<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Groups
{
    private int $id;
    private string $description;
    private bool $inactive;

    public function __construct(int $id, string $description, bool $inactive = false)
    {
        $this->id = $id;
        $this->description = $description;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getInactive(): bool { return $this->inactive; }
}
