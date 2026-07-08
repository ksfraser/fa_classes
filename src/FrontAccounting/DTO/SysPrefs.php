<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SysPrefs
{
    private string $name;
    private string $value;
    private ?string $description;
    private int $category;
    private int $type;
    private int $length;
    private ?int $userId;
    private ?int $companyId;

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
