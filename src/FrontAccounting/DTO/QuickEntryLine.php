<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class QuickEntryLine
{
    private int $id;
    private int $quickEntryId;
    private string $accountCode;
    private string $action;
    private string $amount;
    private string $memo;

    public function __construct(int $id, int $quickEntryId, string $accountCode, string $action, string $amount = '0', string $memo = '')
    {
        $this->id = $id;
        $this->quickEntryId = $quickEntryId;
        $this->accountCode = $accountCode;
        $this->action = $action;
        $this->amount = $amount;
        $this->memo = $memo;
    }

    public function getId(): int { return $this->id; }
    public function getQuickEntryId(): int { return $this->quickEntryId; }
    public function getAccountCode(): string { return $this->accountCode; }
    public function getAction(): string { return $this->action; }
    public function getAmount(): string { return $this->amount; }
    public function getMemo(): string { return $this->memo; }
}
