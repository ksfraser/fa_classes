<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CreditStatus
{
    /** @var int */
    private $id;
    /** @var string */
    private $reasonDescription;
    /** @var bool */
    private $dissallowInvoices;
    /** @var bool */
    private $inactive;

    public function __construct(int $id, string $reasonDescription, bool $dissallowInvoices = false, bool $inactive = false)
    {
        $this->id = $id;
        $this->reasonDescription = $reasonDescription;
        $this->dissallowInvoices = $dissallowInvoices;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getReasonDescription(): string { return $this->reasonDescription; }
    public function isDissallowInvoices(): bool { return $this->dissallowInvoices; }
    public function getInactive(): bool { return $this->inactive; }
}
