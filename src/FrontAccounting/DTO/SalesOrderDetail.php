<?php

namespace FrontAccounting\DTO;

final class SalesOrderDetail
{
    /** @var int */
    private $id;
    /** @var int */
    private $orderNo;
    /** @var int */
    private $transType;
    /** @var string */
    private $stkCode;
    /** @var string */
    private $description;
    /** @var float */
    private $quantity;
    /** @var float */
    private $qtySent;
    /** @var float */
    private $invoiced;

    public function __construct(
        int $id,
        int $orderNo,
        int $transType,
        string $stkCode,
        string $description,
        float $quantity,
        float $qtySent,
        float $invoiced
    ) {
        $this->id = $id;
        $this->orderNo = $orderNo;
        $this->transType = $transType;
        $this->stkCode = $stkCode;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->qtySent = $qtySent;
        $this->invoiced = $invoiced;
    }

    public function getId(): int { return $this->id; }
    public function getOrderNo(): int { return $this->orderNo; }
    public function getTransType(): int { return $this->transType; }
    public function getStkCode(): string { return $this->stkCode; }
    public function getDescription(): string { return $this->description; }
    public function getQuantity(): float { return $this->quantity; }
    public function getQtySent(): float { return $this->qtySent; }
    public function getInvoiced(): float { return $this->invoiced; }
}
