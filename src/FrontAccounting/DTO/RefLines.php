<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class RefLines
{
    private int $id;
    private int $type;
    private string $reference;
    private bool $inactive;

    public function __construct(int $id, int $type, string $reference, bool $inactive = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->reference = $reference;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getReference(): string { return $this->reference; }
    public function getInactive(): bool { return $this->inactive; }
}
