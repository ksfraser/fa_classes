<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Location
{
    private string $locCode;
    private string $locationName;
    private ?string $deliveryAddress;
    private ?string $deliveryPhone;
    private bool $inactive;
    private bool $isDflt;
    private int $taxGroupId;

    public function __construct(
        string $locCode,
        string $locationName,
        ?string $deliveryAddress = null,
        ?string $deliveryPhone = null,
        bool $inactive = false,
        bool $isDflt = false,
        int $taxGroupId = 0
    ) {
        $this->locCode = $locCode;
        $this->locationName = $locationName;
        $this->deliveryAddress = $deliveryAddress;
        $this->deliveryPhone = $deliveryPhone;
        $this->inactive = $inactive;
        $this->isDflt = $isDflt;
        $this->taxGroupId = $taxGroupId;
    }

    public function getLocCode(): string { return $this->locCode; }
    public function getLocationName(): string { return $this->locationName; }
    public function getDeliveryAddress(): ?string { return $this->deliveryAddress; }
    public function getDeliveryPhone(): ?string { return $this->deliveryPhone; }
    public function getInactive(): bool { return $this->inactive; }
    public function isDflt(): bool { return $this->isDflt; }
    public function getTaxGroupId(): int { return $this->taxGroupId; }
}
