<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\BankAccountInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core bank account query functions.
 */
class BankAccountNative implements BankAccountInterface
{
    /**
     * Wrap get_bank_account().
     *
     * @return array<string, mixed>|null
     */
    public function getBankAccount(int $id): ?array
    {
        return \get_bank_account($id);
    }

    /**
     * Wrap get_bank_gl_account().
     */
    public function getBankGlAccount(int $account): int
    {
        return \get_bank_gl_account($account);
    }

    /**
     * Wrap get_branch_accounts().
     *
     * @return array<string, mixed>
     */
    public function getBranchAccounts(int $branchId): array
    {
        return \get_branch_accounts($branchId);
    }

    /**
     * Wrap get_bank_charge_account().
     */
    public function getBankChargeAccount(int $bankAccountId): string
    {
        return \get_bank_charge_account($bankAccountId);
    }
}
