<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CrmCategory
{
    private int $id;
    private string $type;
    private string $action;
    private string $name;
    private string $description;
    private int $system;
    private int $inactive;

    public function __construct(
        int $id,
        string $type,
        string $action,
        string $name,
        string $description,
        int $system,
        int $inactive
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->action = $action;
        $this->name = $name;
        $this->description = $description;
        $this->system = $system;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getType(): string { return $this->type; }
    public function getAction(): string { return $this->action; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getSystem(): int { return $this->system; }
    public function getInactive(): int { return $this->inactive; }
}
