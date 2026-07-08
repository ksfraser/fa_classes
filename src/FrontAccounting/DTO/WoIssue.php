<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class WoIssue
{
    private int $id;
    private int $workOrderId;
    private string $reference;
    private string $stockId;
    private float $qtyIssued;
    private string $date_;
    private ?string $memo;
    private ?int $userId;

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
