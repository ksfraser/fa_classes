<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ItemTaxTypeExemption
{
    private int $id;
    private int $itemTaxTypeId;
    private int $taxTypeId;

    public function __construct(int $id, int $itemTaxTypeId, int $taxTypeId)
    {
        $this->id = $id;
        $this->itemTaxTypeId = $itemTaxTypeId;
        $this->taxTypeId = $taxTypeId;
    }

    public function getId(): int { return $this->id; }
    public function getItemTaxTypeId(): int { return $this->itemTaxTypeId; }
    public function getTaxTypeId(): int { return $this->taxTypeId; }
}
