<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for GL transaction operations.
 */
interface GlTransService
{
    public function addGlTrans(
        int $type,
        int $typeNo,
        string $tranDate,
        string $account,
        float $dimensionId,
        float $dimension2Id,
        string $memo,
        float $amount,
        ?string $personCurrency = null,
        string $personType = '',
        int $personId = 0
    ): float;

    public function addGlTransCustomer(
        int $type,
        int $typeNo,
        string $tranDate,
        string $account,
        float $dimensionId,
        float $dimension2Id,
        float $amount,
        int $customerId,
        string $errorMsg = ''
    ): float;
}
