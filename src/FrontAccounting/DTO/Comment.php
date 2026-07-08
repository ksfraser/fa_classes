<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Comment
{
    private int $id;
    private int $type;
    private int $typeNo;
    private ?string $date_;
    private string $memo;
    private ?string $userEmail;

    public function __construct(
        int $id,
        int $type,
        int $typeNo,
        ?string $date_ = null,
        string $memo = '',
        ?string $userEmail = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->typeNo = $typeNo;
        $this->date_ = $date_;
        $this->memo = $memo;
        $this->userEmail = $userEmail;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getTypeNo(): int { return $this->typeNo; }
    public function getDate(): ?string { return $this->date_; }
    public function getMemo(): string { return $this->memo; }
    public function getUserEmail(): ?string { return $this->userEmail; }
}
