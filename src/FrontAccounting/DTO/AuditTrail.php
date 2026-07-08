<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class AuditTrail
{
    private int $id;
    private int $type;
    private int $transNo;
    private ?int $userId;
    private ?string $stamp;
    private ?string $description;
    private ?string $sql;

    public function __construct(
        int $id,
        int $type,
        int $transNo,
        ?int $userId = null,
        ?string $stamp = null,
        ?string $description = null,
        ?string $sql = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->transNo = $transNo;
        $this->userId = $userId;
        $this->stamp = $stamp;
        $this->description = $description;
        $this->sql = $sql;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getTransNo(): int { return $this->transNo; }
    public function getUserId(): ?int { return $this->userId; }
    public function getStamp(): ?string { return $this->stamp; }
    public function getDescription(): ?string { return $this->description; }
    public function getSql(): ?string { return $this->sql; }
}
