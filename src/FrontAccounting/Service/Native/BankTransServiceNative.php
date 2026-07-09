<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\BankTransService;

/**
 * @since 2026-07-09
 * Native implementation of BankTransService wrapping FA core bank functions.
 */
class BankTransServiceNative implements BankTransService
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
