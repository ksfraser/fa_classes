<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class ItemTaxTypeExemption
{
    /** @var int */
    private $id;
    /** @var int */
    private $itemTaxTypeId;
    /** @var int */
    private $taxTypeId;

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
