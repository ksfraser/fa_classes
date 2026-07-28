<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WoIssue
{
    /** @var int */
    private $id;
    /** @var int */
    private $workOrderId;
    /** @var string */
    private $reference;
    /** @var string */
    private $stockId;
    /** @var float */
    private $qtyIssued;
    /** @var string */
    private $date_;
    /** @var ?string */
    private $memo;
    /** @var ?int */
    private $userId;

    public function __construct(
        int $id,
        int $workOrderId,
        string $reference,
        string $stockId,
        float $qtyIssued,
        string $date_ = '',
        ?string $memo = null,
        ?int $userId = null
    ) {
        $this->id = $id;
        $this->workOrderId = $workOrderId;
        $this->reference = $reference;
        $this->stockId = $stockId;
        $this->qtyIssued = $qtyIssued;
        $this->date_ = $date_;
        $this->memo = $memo;
        $this->userId = $userId;
    }

    public function getId(): int { return $this->id; }
    public function getWorkOrderId(): int { return $this->workOrderId; }
    public function getReference(): string { return $this->reference; }
    public function getStockId(): string { return $this->stockId; }
    public function getQtyIssued(): float { return $this->qtyIssued; }
    public function getDate(): string { return $this->date_; }
    public function getMemo(): ?string { return $this->memo; }
    public function getUserId(): ?int { return $this->userId; }
}
