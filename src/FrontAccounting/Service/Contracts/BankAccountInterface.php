<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for bank account query operations.
 */
interface BankAccountInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getBankAccount(int $id): ?array;

    public function getBankGlAccount(int $account): int;

    /**
     * @return array<string, mixed>
     */
    public function getBranchAccounts(int $branchId): array;

    public function getBankChargeAccount(int $bankAccountId): string;
}
