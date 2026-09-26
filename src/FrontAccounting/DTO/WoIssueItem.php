<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WoIssueItem
{
    /** @var int */
    private $id;
    /** @var int */
    private $issueId;
    /** @var string */
    private $stockId;
    /** @var float */
    private $qtyIssued;
    /** @var string */
    private $date_;

    public function __construct(int $id, int $issueId, string $stockId, float $qtyIssued, string $date_ = '')
    {
        $this->id = $id;
        $this->issueId = $issueId;
        $this->stockId = $stockId;
        $this->qtyIssued = $qtyIssued;
        $this->date_ = $date_;
    }

    public function getId(): int { return $this->id; }
    public function getIssueId(): int { return $this->issueId; }
    public function getStockId(): string { return $this->stockId; }
    public function getQtyIssued(): float { return $this->qtyIssued; }
    public function getDate(): string { return $this->date_; }
}
