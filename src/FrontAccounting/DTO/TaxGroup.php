<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TaxGroup
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var bool */
    private $inactive;

    public function __construct(int $id, string $name, bool $inactive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getInactive(): bool { return $this->inactive; }
}
