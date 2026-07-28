<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class GrnBatch
{
    /** @var int */
    private $id;
    /** @var int */
    private $purchOrderNo;
    /** @var ?string */
    private $reference;
    /** @var ?string */
    private $ordDate;
    /** @var ?string */
    private $deliveryDate;
    /** @var ?string */
    private $dueDate;
    /** @var string */
    private $location;
    /** @var bool */
    private $isReceived;
    /** @var bool */
    private $isPartial;

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
