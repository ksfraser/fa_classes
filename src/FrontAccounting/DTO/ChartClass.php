<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ChartClass
{
    private int $cid;
    private string $name;
    private string $ctype;
    private bool $inactive;

    public function __construct(int $cid, string $name, string $ctype, bool $inactive = false)
    {
        $this->cid = $cid;
        $this->name = $name;
        $this->ctype = $ctype;
        $this->inactive = $inactive;
    }

    public function getCid(): int { return $this->cid; }
    public function getName(): string { return $this->name; }
    public function getCtype(): string { return $this->ctype; }
    public function getInactive(): bool { return $this->inactive; }
}
