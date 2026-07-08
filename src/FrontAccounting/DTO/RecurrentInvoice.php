<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class RecurrentInvoice
{
    private int $id;
    private string $description;
    private int $orderNo;
    private int $debtorNo;
    private int $branchCode;
    private int $group_;
    private int $salesType;
    private string $date_;
    private ?string $endDate;
    private int $templateNo;
    private int $isTemplate;
    private string $memo;
    private bool $inactive;

    public function __construct(
        int $id,
        string $description,
        int $orderNo,
        int $debtorNo,
        int $branchCode,
        int $group_,
        int $salesType,
        string $date_,
        ?string $endDate = null,
        int $templateNo = 0,
        int $isTemplate = 0,
        string $memo = '',
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->orderNo = $orderNo;
        $this->debtorNo = $debtorNo;
        $this->branchCode = $branchCode;
        $this->group_ = $group_;
        $this->salesType = $salesType;
        $this->date_ = $date_;
        $this->endDate = $endDate;
        $this->templateNo = $templateNo;
        $this->isTemplate = $isTemplate;
        $this->memo = $memo;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getOrderNo(): int { return $this->orderNo; }
    public function getDebtorNo(): int { return $this->debtorNo; }
    public function getBranchCode(): int { return $this->branchCode; }
    public function getGroup(): int { return $this->group_; }
    public function getSalesType(): int { return $this->salesType; }
    public function getDate(): string { return $this->date_; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function getTemplateNo(): int { return $this->templateNo; }
    public function isTemplate(): int { return $this->isTemplate; }
    public function getMemo(): string { return $this->memo; }
    public function getInactive(): bool { return $this->inactive; }
}
