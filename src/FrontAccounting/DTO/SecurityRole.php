<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SecurityRole
{
    private int $id;
    private string $role;
    private string $description;
    private string $sections;
    private string $areas;
    private bool $inactive;

    public function __construct(
        int $id,
        string $role,
        string $description,
        string $sections = '',
        string $areas = '',
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->role = $role;
        $this->description = $description;
        $this->sections = $sections;
        $this->areas = $areas;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getRole(): string { return $this->role; }
    public function getDescription(): string { return $this->description; }
    public function getSections(): string { return $this->sections; }
    public function getAreas(): string { return $this->areas; }
    public function getInactive(): bool { return $this->inactive; }
}
