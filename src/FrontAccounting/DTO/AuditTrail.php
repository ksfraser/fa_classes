<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class AuditTrail
{
    /** @var int */
    private $id;
    /** @var int */
    private $type;
    /** @var int */
    private $transNo;
    /** @var ?int */
    private $userId;
    /** @var ?string */
    private $stamp;
    /** @var ?string */
    private $description;
    /** @var ?string */
    private $sql;

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
