<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SalesPerson
{
    /** @var int */
    private $salesmanCode;
    /** @var string */
    private $salesmanName;
    /** @var ?string */
    private $salesmanPhone;
    /** @var ?string */
    private $salesmanFax;
    /** @var ?string */
    private $salesmanEmail;
    /** @var ?float */
    private $provision;
    /** @var ?float */
    private $breakPt;
    /** @var ?float */
    private $provision2;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $salesmanCode,
        string $salesmanName,
        ?string $salesmanPhone = null,
        ?string $salesmanFax = null,
        ?string $salesmanEmail = null,
        ?float $provision = null,
        ?float $breakPt = null,
        ?float $provision2 = null,
        bool $inactive = false
    ) {
        $this->salesmanCode = $salesmanCode;
        $this->salesmanName = $salesmanName;
        $this->salesmanPhone = $salesmanPhone;
        $this->salesmanFax = $salesmanFax;
        $this->salesmanEmail = $salesmanEmail;
        $this->provision = $provision;
        $this->breakPt = $breakPt;
        $this->provision2 = $provision2;
        $this->inactive = $inactive;
    }

    public function getSalesmanCode(): int { return $this->salesmanCode; }
    public function getSalesmanName(): string { return $this->salesmanName; }
    public function getSalesmanPhone(): ?string { return $this->salesmanPhone; }
    public function getSalesmanFax(): ?string { return $this->salesmanFax; }
    public function getSalesmanEmail(): ?string { return $this->salesmanEmail; }
    public function getProvision(): ?float { return $this->provision; }
    public function getBreakPt(): ?float { return $this->breakPt; }
    public function getProvision2(): ?float { return $this->provision2; }
    public function getInactive(): bool { return $this->inactive; }
}
