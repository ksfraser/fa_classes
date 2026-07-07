<?php

namespace FrontAccounting\DTO;

final class GrnItem
{
    /** @var int */
    private $id;
    /** @var int */
    private $grnBatchId;
    /** @var int */
    private $poDetailItem;
    /** @var string */
    private $itemCode;
    /** @var string */
    private $description;
    /** @var float */
    private $qtyRecd;
    /** @var float */
    private $qtyInv;

    public function __construct(
        int $id,
        int $grnBatchId,
        int $poDetailItem,
        string $itemCode,
        string $description,
        float $qtyRecd,
        float $qtyInv
    ) {
        $this->id = $id;
        $this->grnBatchId = $grnBatchId;
        $this->poDetailItem = $poDetailItem;
        $this->itemCode = $itemCode;
        $this->description = $description;
        $this->qtyRecd = $qtyRecd;
        $this->qtyInv = $qtyInv;
    }

    public function getId(): int { return $this->id; }
    public function getGrnBatchId(): int { return $this->grnBatchId; }
    public function getPoDetailItem(): int { return $this->poDetailItem; }
    public function getItemCode(): string { return $this->itemCode; }
    public function getDescription(): string { return $this->description; }
    public function getQtyRecd(): float { return $this->qtyRecd; }
    public function getQtyInv(): float { return $this->qtyInv; }
}
