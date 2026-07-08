<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Dimension
{
    private int $id;
    private string $reference;
    private string $name;
    private int $type;
    private bool $closed;
    private ?string $date_;
    private ?string $dueDate;
    private bool $inactive;

    public function __construct(
        int $id,
        string $reference,
        string $name,
        int $type,
        bool $closed = false,
        ?string $date_ = null,
        ?string $dueDate = null,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->name = $name;
        $this->type = $type;
        $this->closed = $closed;
        $this->date_ = $date_;
        $this->dueDate = $dueDate;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getReference(): string { return $this->reference; }
    public function getName(): string { return $this->name; }
    public function getType(): int { return $this->type; }
    public function isClosed(): bool { return $this->closed; }
    public function getDate(): ?string { return $this->date_; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getInactive(): bool { return $this->inactive; }
}
