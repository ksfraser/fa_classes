<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Journal
{
    /** @var int */
    private $type;
    /** @var int */
    private $typeNo;
    /** @var string */
    private $tranDate;
    /** @var string */
    private $reference;
    /** @var string */
    private $memo;
    /** @var ?string */
    private $eventDate;
    /** @var bool */
    private $recurrent;
    /** @var int */
    private $recurring;
    /** @var ?int */
    private $userId;

    public function __construct(
        int $type,
        int $typeNo,
        string $tranDate,
        string $reference,
        string $memo,
        ?string $eventDate = null,
        bool $recurrent = false,
        int $recurring = 0,
        ?int $userId = null
    ) {
        $this->type = $type;
        $this->typeNo = $typeNo;
        $this->tranDate = $tranDate;
        $this->reference = $reference;
        $this->memo = $memo;
        $this->eventDate = $eventDate;
        $this->recurrent = $recurrent;
        $this->recurring = $recurring;
        $this->userId = $userId;
    }

    public function getType(): int { return $this->type; }
    public function getTypeNo(): int { return $this->typeNo; }
    public function getTranDate(): string { return $this->tranDate; }
    public function getReference(): string { return $this->reference; }
    public function getMemo(): string { return $this->memo; }
    public function getEventDate(): ?string { return $this->eventDate; }
    public function isRecurrent(): bool { return $this->recurrent; }
    public function getRecurring(): int { return $this->recurring; }
    public function getUserId(): ?int { return $this->userId; }
}
