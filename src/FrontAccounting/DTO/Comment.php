<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Comment
{
    /** @var int */
    private $id;
    /** @var int */
    private $type;
    /** @var int */
    private $typeNo;
    /** @var ?string */
    private $date_;
    /** @var string */
    private $memo;
    /** @var ?string */
    private $userEmail;

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
