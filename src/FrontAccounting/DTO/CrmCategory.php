<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CrmCategory
{
    /** @var int */
    private $id;
    /** @var string */
    private $type;
    /** @var string */
    private $action;
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var int */
    private $system;
    /** @var int */
    private $inactive;

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
