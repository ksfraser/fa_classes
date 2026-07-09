<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\DebtorTransInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core debtor transaction functions.
 */
class DebtorTransNative implements DebtorTransInterface
{
    /**
     * Wrap write_customer_trans() — creates the debtor_trans record.
     *
     * @return int  The new transaction number
     */
    public function writeCustomerTrans(
        int $transType,
        int $transNo,
        int $customerId,
        int $branchId,
        string $date_,
        string $ref,
        float $amount,
        float $discount = 0.0
    ): int {
        return \write_customer_trans(
            $transType, $transNo, $customerId, $branchId,
            $date_, $ref, $amount, $discount
        );
    }

    /**
     * Wrap get_customer_trans().
     *
     * @return array<string, mixed>
     */
    public function getCustomerTrans(int $transNo, int $transType): array
    {
        return \get_customer_trans($transNo, $transType);
    }
}
