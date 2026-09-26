<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class StockCategory
{
    /** @var int */
    private $categoryId;
    /** @var string */
    private $description;
    /** @var string|null */
    private $longDescription;
    /** @var int */
    private $dfltTaxType;
    /** @var int */
    private $dfltUnits;
    /** @var int */
    private $dfltMbFlag;
    /** @var string|null */
    private $dfltSalesAccount;
    /** @var string|null */
    private $dfltInventoryAccount;
    /** @var string|null */
    private $dfltCogsAccount;
    /** @var string|null */
    private $dfltAdjustmentAccount;
    /** @var string|null */
    private $dfltAssemblyAccount;
    /** @var string|null */
    private $dfltDimAccount;
    /** @var string|null */
    private $dfltWipAccount;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $categoryId,
        string $description,
        ?string $longDescription = null,
        int $dfltTaxType = 0,
        int $dfltUnits = 0,
        int $dfltMbFlag = 0,
        ?string $dfltSalesAccount = null,
        ?string $dfltInventoryAccount = null,
        ?string $dfltCogsAccount = null,
        ?string $dfltAdjustmentAccount = null,
        ?string $dfltAssemblyAccount = null,
        ?string $dfltDimAccount = null,
        ?string $dfltWipAccount = null,
        bool $inactive = false
    ) {
        $this->categoryId = $categoryId;
        $this->description = $description;
        $this->longDescription = $longDescription;
        $this->dfltTaxType = $dfltTaxType;
        $this->dfltUnits = $dfltUnits;
        $this->dfltMbFlag = $dfltMbFlag;
        $this->dfltSalesAccount = $dfltSalesAccount;
        $this->dfltInventoryAccount = $dfltInventoryAccount;
        $this->dfltCogsAccount = $dfltCogsAccount;
        $this->dfltAdjustmentAccount = $dfltAdjustmentAccount;
        $this->dfltAssemblyAccount = $dfltAssemblyAccount;
        $this->dfltDimAccount = $dfltDimAccount;
        $this->dfltWipAccount = $dfltWipAccount;
        $this->inactive = $inactive;
    }

    public function getCategoryId(): int { return $this->categoryId; }
    public function getDescription(): string { return $this->description; }
    public function getLongDescription(): ?string { return $this->longDescription; }
    public function getDfltTaxType(): int { return $this->dfltTaxType; }
    public function getDfltUnits(): int { return $this->dfltUnits; }
    public function getDfltMbFlag(): int { return $this->dfltMbFlag; }
    public function getDfltSalesAccount(): ?string { return $this->dfltSalesAccount; }
    public function getDfltInventoryAccount(): ?string { return $this->dfltInventoryAccount; }
    public function getDfltCogsAccount(): ?string { return $this->dfltCogsAccount; }
    public function getDfltAdjustmentAccount(): ?string { return $this->dfltAdjustmentAccount; }
    public function getDfltAssemblyAccount(): ?string { return $this->dfltAssemblyAccount; }
    public function getDfltDimAccount(): ?string { return $this->dfltDimAccount; }
    public function getDfltWipAccount(): ?string { return $this->dfltWipAccount; }
    public function getInactive(): bool { return $this->inactive; }
}
