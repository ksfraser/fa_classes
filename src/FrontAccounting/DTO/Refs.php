<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Refs
{
    /** @var int */
    private $id;
    /** @var int */
    private $type;
    /** @var int */
    private $transNo;
    /** @var string */
    private $reference;
    /** @var ?string */
    private $description;

    public function __construct(int $id, int $type, int $transNo, string $reference, ?string $description = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->transNo = $transNo;
        $this->reference = $reference;
        $this->description = $description;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getTransNo(): int { return $this->transNo; }
    public function getReference(): string { return $this->reference; }
    public function getDescription(): ?string { return $this->description; }
}
