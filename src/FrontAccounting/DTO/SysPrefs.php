<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SysPrefs
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;
    /** @var ?string */
    private $description;
    /** @var int */
    private $category;
    /** @var int */
    private $type;
    /** @var int */
    private $length;
    /** @var ?int */
    private $userId;
    /** @var ?int */
    private $companyId;

    public function __construct(
        string $name,
        string $value,
        ?string $description = null,
        int $category = 0,
        int $type = 0,
        int $length = 0,
        ?int $userId = null,
        ?int $companyId = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->category = $category;
        $this->type = $type;
        $this->length = $length;
        $this->userId = $userId;
        $this->companyId = $companyId;
    }

    public function getName(): string { return $this->name; }
    public function getValue(): string { return $this->value; }
    public function getDescription(): ?string { return $this->description; }
    public function getCategory(): int { return $this->category; }
    public function getType(): int { return $this->type; }
    public function getLength(): int { return $this->length; }
    public function getUserId(): ?int { return $this->userId; }
    public function getCompanyId(): ?int { return $this->companyId; }
}
