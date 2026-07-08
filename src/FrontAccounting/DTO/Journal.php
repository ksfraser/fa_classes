<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Journal
{
    private int $type;
    private int $typeNo;
    private string $tranDate;
    private string $reference;
    private string $memo;
    private ?string $eventDate;
    private bool $recurrent;
    private int $recurring;
    private ?int $userId;

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
