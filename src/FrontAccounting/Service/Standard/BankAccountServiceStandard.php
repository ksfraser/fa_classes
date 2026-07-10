<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\BankAccountsRepository;
use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Service\Contracts\BankAccountService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of BankAccountService.
 *
 * Queries bank_accounts and cust_branch tables directly instead of
 * delegating to FA core's get_bank_account() / get_branch_accounts().
 *
 * ┌───────────────────────────────────────────────────────────────┐
 * │                  BankAccountServiceStandard                    │
 * │  - bankAccountRepo: BankAccountsRepository                    │
 * │  - branchRepo:      CustomerBranchRepository                  │
 * ├───────────────────────────────────────────────────────────────┤
 * │  + getBankAccount($id): ?array                                │
 * │  + getBankGlAccount($account): int                            │
 * │  + getBranchAccounts($branchId): array                        │
 * │  + getBankChargeAccount($bankAccountId): string               │
 * └───────────────────────────────────────────────────────────────┘
 */
final class BankAccountServiceStandard implements BankAccountService
{
    private BankAccountsRepository $bankAccountRepo;
    private CustomerBranchRepository $branchRepo;

    public function __construct(
        BankAccountsRepository $bankAccountRepo,
        CustomerBranchRepository $branchRepo
    ) {
        $this->bankAccountRepo = $bankAccountRepo;
        $this->branchRepo = $branchRepo;
    }

    public function getBankAccount(int $id): ?array
    {
        $row = $this->bankAccountRepo->findOneWhere(['id' => $id]);
        return $row !== false ? $row : null;
    }

    public function getBankGlAccount(int $account): int
    {
        $row = $this->bankAccountRepo->findOneWhere(['id' => $account]);
        return $row ? (int)($row['bank_gl_account'] ?? 0) : 0;
    }

    /**
     * @return array<string, mixed>
     */
    public function getBranchAccounts(int $branchId): array
    {
        $row = $this->branchRepo->findOneWhere(['branch_code' => $branchId]);
        if ($row === null) {
            return ['receivables_account' => '', 'payment_discount_account' => ''];
        }
        return [
            'receivables_account' => (string)($row['receivables_account'] ?? ''),
            'payment_discount_account' => (string)($row['payment_discount_account'] ?? ''),
        ];
    }

    public function getBankChargeAccount(int $bankAccountId): string
    {
        $row = $this->bankAccountRepo->findOneWhere(['id' => $bankAccountId]);
        return $row ? (string)($row['bank_charge_act'] ?? '') : '';
    }
}
