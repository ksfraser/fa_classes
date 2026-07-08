<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class StockMaster
{
    private string $stockId;
    private int $categoryId;
    private int $taxTypeId;
    private string $description;
    private string $longDescription;
    private string $units;
    private string $mbFlag;
    private string $salesAccount;
    private string $cogsAccount;
    private string $inventoryAccount;
    private string $adjustmentAccount;
    private string $wipAccount;
    private ?int $dimensionId;
    private ?int $dimension2Id;
    private float $purchaseCost;
    private float $materialCost;
    private float $labourCost;
    private float $overheadCost;
    private int $inactive;
    private int $noSale;
    private int $noPurchase;
    private int $editable;

    public function __construct(
        string $stockId,
        int $categoryId,
        int $taxTypeId,
        string $description,
        string $longDescription,
        string $units,
        string $mbFlag,
        string $salesAccount,
        string $cogsAccount,
        string $inventoryAccount,
        string $adjustmentAccount,
        string $wipAccount,
        ?int $dimensionId,
        ?int $dimension2Id,
        float $purchaseCost,
        float $materialCost,
        float $labourCost,
        float $overheadCost,
        int $inactive,
        int $noSale,
        int $noPurchase,
        int $editable
    ) {
        $this->stockId = $stockId;
        $this->categoryId = $categoryId;
        $this->taxTypeId = $taxTypeId;
        $this->description = $description;
        $this->longDescription = $longDescription;
        $this->units = $units;
        $this->mbFlag = $mbFlag;
        $this->salesAccount = $salesAccount;
        $this->cogsAccount = $cogsAccount;
        $this->inventoryAccount = $inventoryAccount;
        $this->adjustmentAccount = $adjustmentAccount;
        $this->wipAccount = $wipAccount;
        $this->dimensionId = $dimensionId;
        $this->dimension2Id = $dimension2Id;
        $this->purchaseCost = $purchaseCost;
        $this->materialCost = $materialCost;
        $this->labourCost = $labourCost;
        $this->overheadCost = $overheadCost;
        $this->inactive = $inactive;
        $this->noSale = $noSale;
        $this->noPurchase = $noPurchase;
        $this->editable = $editable;
    }

    public function getStockId(): string { return $this->stockId; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function getTaxTypeId(): int { return $this->taxTypeId; }
    public function getDescription(): string { return $this->description; }
    public function getLongDescription(): string { return $this->longDescription; }
    public function getUnits(): string { return $this->units; }
    public function getMbFlag(): string { return $this->mbFlag; }
    public function getSalesAccount(): string { return $this->salesAccount; }
    public function getCogsAccount(): string { return $this->cogsAccount; }
    public function getInventoryAccount(): string { return $this->inventoryAccount; }
    public function getAdjustmentAccount(): string { return $this->adjustmentAccount; }
    public function getWipAccount(): string { return $this->wipAccount; }
    public function getDimensionId(): ?int { return $this->dimensionId; }
    public function getDimension2Id(): ?int { return $this->dimension2Id; }
    public function getPurchaseCost(): float { return $this->purchaseCost; }
    public function getMaterialCost(): float { return $this->materialCost; }
    public function getLabourCost(): float { return $this->labourCost; }
    public function getOverheadCost(): float { return $this->overheadCost; }
    public function getInactive(): int { return $this->inactive; }
    public function getNoSale(): int { return $this->noSale; }
    public function getNoPurchase(): int { return $this->noPurchase; }
    public function getEditable(): int { return $this->editable; }
}
