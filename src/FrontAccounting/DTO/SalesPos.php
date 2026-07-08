<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SalesPos
{
    private int $id;
    private string $posName;
    private int $cashAccount;
    private int $creditCardAccount;
    private int $exchangeDiffAccount;
    private int $discountAccount;
    private int $defaultSalesType;
    private bool $inactive;

    public function __construct(
        int $id,
        string $posName,
        int $cashAccount = 0,
        int $creditCardAccount = 0,
        int $exchangeDiffAccount = 0,
        int $discountAccount = 0,
        int $defaultSalesType = 0,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->posName = $posName;
        $this->cashAccount = $cashAccount;
        $this->creditCardAccount = $creditCardAccount;
        $this->exchangeDiffAccount = $exchangeDiffAccount;
        $this->discountAccount = $discountAccount;
        $this->defaultSalesType = $defaultSalesType;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getPosName(): string { return $this->posName; }
    public function getCashAccount(): int { return $this->cashAccount; }
    public function getCreditCardAccount(): int { return $this->creditCardAccount; }
    public function getExchangeDiffAccount(): int { return $this->exchangeDiffAccount; }
    public function getDiscountAccount(): int { return $this->discountAccount; }
    public function getDefaultSalesType(): int { return $this->defaultSalesType; }
    public function getInactive(): bool { return $this->inactive; }
}
