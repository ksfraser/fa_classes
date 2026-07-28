<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Tag
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var bool */
    private $inactive;

    public function __construct(int $id, string $name, string $description = '', bool $inactive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getInactive(): bool { return $this->inactive; }
}
