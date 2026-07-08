<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TaxGroupItem
{
    private int $id;
    private int $taxGroupId;
    private int $taxTypeId;
    private float $rate;

    public function __construct(int $id, int $taxGroupId, int $taxTypeId, float $rate = 0.0)
    {
        $this->id = $id;
        $this->taxGroupId = $taxGroupId;
        $this->taxTypeId = $taxTypeId;
        $this->rate = $rate;
    }

    public function getId(): int { return $this->id; }
    public function getTaxGroupId(): int { return $this->taxGroupId; }
    public function getTaxTypeId(): int { return $this->taxTypeId; }
    public function getRate(): float { return $this->rate; }
}
