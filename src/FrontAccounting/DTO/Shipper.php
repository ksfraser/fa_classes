<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Shipper
{
    /** @var int */
    private $shipperId;
    /** @var string */
    private $shipperName;
    /** @var string */
    private $contact;
    /** @var string */
    private $phone;
    /** @var string */
    private $phone2;
    /** @var string */
    private $email;
    /** @var string */
    private $website;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $shipperId,
        string $shipperName,
        string $contact = '',
        string $phone = '',
        string $phone2 = '',
        string $email = '',
        string $website = '',
        bool $inactive = false
    ) {
        $this->shipperId = $shipperId;
        $this->shipperName = $shipperName;
        $this->contact = $contact;
        $this->phone = $phone;
        $this->phone2 = $phone2;
        $this->email = $email;
        $this->website = $website;
        $this->inactive = $inactive;
    }

    public function getShipperId(): int { return $this->shipperId; }
    public function getShipperName(): string { return $this->shipperName; }
    public function getContact(): string { return $this->contact; }
    public function getPhone(): string { return $this->phone; }
    public function getPhone2(): string { return $this->phone2; }
    public function getEmail(): string { return $this->email; }
    public function getWebsite(): string { return $this->website; }
    public function getInactive(): bool { return $this->inactive; }
}
