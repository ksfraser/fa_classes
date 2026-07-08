<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class GrnBatch
{
    private int $id;
    private int $purchOrderNo;
    private ?string $reference;
    private ?string $ordDate;
    private ?string $deliveryDate;
    private ?string $dueDate;
    private string $location;
    private bool $isReceived;
    private bool $isPartial;

    public function __construct(
        int $id,
        int $purchOrderNo,
        ?string $reference = null,
        ?string $ordDate = null,
        ?string $deliveryDate = null,
        ?string $dueDate = null,
        string $location = '',
        bool $isReceived = false,
        bool $isPartial = false
    ) {
        $this->id = $id;
        $this->purchOrderNo = $purchOrderNo;
        $this->reference = $reference;
        $this->ordDate = $ordDate;
        $this->deliveryDate = $deliveryDate;
        $this->dueDate = $dueDate;
        $this->location = $location;
        $this->isReceived = $isReceived;
        $this->isPartial = $isPartial;
    }

    public function getId(): int { return $this->id; }
    public function getPurchOrderNo(): int { return $this->purchOrderNo; }
    public function getReference(): ?string { return $this->reference; }
    public function getOrdDate(): ?string { return $this->ordDate; }
    public function getDeliveryDate(): ?string { return $this->deliveryDate; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getLocation(): string { return $this->location; }
    public function isReceived(): bool { return $this->isReceived; }
    public function isPartial(): bool { return $this->isPartial; }
}
