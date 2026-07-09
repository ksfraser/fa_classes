<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * Native wrapper for FA core GL transaction functions.
 *
 * Wraps add_gl_trans() and add_gl_trans_customer() from
 * gl/includes/db/gl_db.inc and sales/includes/db/sales_db.inc.
 */
class GlTransNative
{
    /**
     * Wrap add_gl_trans().
     *
     * @return float  The amount posted (for total tracking)
     */
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
    ): float {
        return \add_gl_trans(
            $type, $typeNo, $tranDate, $account,
            $dimensionId, $dimension2Id, $memo, $amount,
            $personCurrency, $personType, $personId
        );
    }

    /**
     * Wrap add_gl_trans_customer().
     *
     * @return float  The amount posted
     */
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
    ): float {
        return \add_gl_trans_customer(
            $type, $typeNo, $tranDate, $account,
            $dimensionId, $dimension2Id, $amount,
            $customerId, $errorMsg
        );
    }
}
