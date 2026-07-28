<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TaxType
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $taxTypeName;
    /** @var float */
    private $rate;
    /** @var string */
    private $salesGlCode;
    /** @var string */
    private $purchasingGlCode;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $id,
        string $name,
        string $taxTypeName,
        float $rate,
        string $salesGlCode = '',
        string $purchasingGlCode = '',
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->taxTypeName = $taxTypeName;
        $this->rate = $rate;
        $this->salesGlCode = $salesGlCode;
        $this->purchasingGlCode = $purchasingGlCode;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getTaxTypeName(): string { return $this->taxTypeName; }
    public function getRate(): float { return $this->rate; }
    public function getSalesGlCode(): string { return $this->salesGlCode; }
    public function getPurchasingGlCode(): string { return $this->purchasingGlCode; }
    public function getInactive(): bool { return $this->inactive; }
}
