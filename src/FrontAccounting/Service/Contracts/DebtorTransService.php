<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for debtor transaction operations.
 */
interface DebtorTransService
{
    public function writeCustomerTrans(
        int $transType,
        int $transNo,
        int $customerId,
        int $branchId,
        string $date_,
        string $ref,
        float $amount,
        float $discount = 0.0
    ): int;

    /**
     * @return array<string, mixed>
     */
    public function getCustomerTrans(int $transNo, int $transType): array;
}
