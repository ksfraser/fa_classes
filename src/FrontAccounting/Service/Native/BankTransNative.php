<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\BankTransInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core bank transaction functions.
 */
class BankTransNative implements BankTransInterface
{
    /**
     * Wrap add_bank_trans().
     */
    public function addBankTrans(
        int $type,
        int $transNo,
        int $bankAccount,
        string $ref,
        string $date_,
        float $amount,
        string $personType = '',
        int $personId = 0
    ): bool {
        return (bool)\add_bank_trans(
            $type, $transNo, $bankAccount, $ref,
            $date_, $amount, $personType, $personId
        );
    }

    /**
     * Wrap void_bank_trans().
     */
    public function voidBankTrans(int $type, int $transNo, bool $isEditing = true): void
    {
        \void_bank_trans($type, $transNo, $isEditing);
    }
}
