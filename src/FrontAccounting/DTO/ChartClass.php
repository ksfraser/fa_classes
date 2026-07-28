<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ChartClass
{
    /** @var int */
    private $cid;
    /** @var string */
    private $name;
    /** @var string */
    private $ctype;
    /** @var bool */
    private $inactive;

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
