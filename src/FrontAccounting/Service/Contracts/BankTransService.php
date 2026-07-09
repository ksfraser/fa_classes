<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for bank transaction operations.
 */
interface BankTransService
{
    public function addBankTrans(
        int $type,
        int $transNo,
        int $bankAccount,
        string $ref,
        string $date_,
        float $amount,
        string $personType = '',
        int $personId = 0
    ): bool;

    public function voidBankTrans(int $type, int $transNo, bool $isEditing = true): void;
}
