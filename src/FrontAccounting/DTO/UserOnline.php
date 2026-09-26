<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class UserOnline
{
    /** @var int */
    private $id;
    /** @var int */
    private $userId;
    /** @var string|null */
    private $ipAddress;
    /** @var string|null */
    private $time_;
    /** @var string|null */
    private $date_;
    /** @var string|null */
    private $currDate;
    /** @var string|null */
    private $lastCheck;

    public function __construct(
        int $id,
        int $userId,
        ?string $ipAddress = null,
        ?string $time_ = null,
        ?string $date_ = null,
        ?string $currDate = null,
        ?string $lastCheck = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->ipAddress = $ipAddress;
        $this->time_ = $time_;
        $this->date_ = $date_;
        $this->currDate = $currDate;
        $this->lastCheck = $lastCheck;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getIpAddress(): ?string { return $this->ipAddress; }
    public function getTime(): ?string { return $this->time_; }
    public function getDate(): ?string { return $this->date_; }
    public function getCurrDate(): ?string { return $this->currDate; }
    public function getLastCheck(): ?string { return $this->lastCheck; }
}
