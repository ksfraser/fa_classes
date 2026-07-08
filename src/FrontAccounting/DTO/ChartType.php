<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ChartType
{
    private int $id;
    private string $name;
    private int $classId;
    private ?int $parent;
    private bool $inactive;

    public function __construct(int $id, string $name, int $classId, ?int $parent = null, bool $inactive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->classId = $classId;
        $this->parent = $parent;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getClassId(): int { return $this->classId; }
    public function getParent(): ?int { return $this->parent; }
    public function getInactive(): bool { return $this->inactive; }
}
