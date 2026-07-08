<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class FiscalYear
{
    private int $id;
    private string $begin;
    private string $end;
    private bool $closed;
    private bool $isActive;

    public function __construct(int $id, string $begin, string $end, bool $closed = false, bool $isActive = false)
    {
        $this->id = $id;
        $this->begin = $begin;
        $this->end = $end;
        $this->closed = $closed;
        $this->isActive = $isActive;
    }

    public function getId(): int { return $this->id; }
    public function getBegin(): string { return $this->begin; }
    public function getEnd(): string { return $this->end; }
    public function isClosed(): bool { return $this->closed; }
    public function isActive(): bool { return $this->isActive; }
}
