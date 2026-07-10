<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Service\Contracts\GlTransService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of GlTransService.
 *
 * Inserts gl_trans rows directly via GlTransRepository instead of
 * delegating to FA core's \add_gl_trans() / \add_gl_trans_customer().
 *
 * ┌──────────────────────────────────────────────────────────────┐
 * │                   GlTransServiceStandard                      │
 * │  - glTransRepo: GlTransRepository                             │
 * ├──────────────────────────────────────────────────────────────┤
 * │  + addGlTrans(...): float                                     │
 * │  + addGlTransCustomer(...): float                             │
 * ├──────────────────────────────────────────────────────────────┤
 * │  1. Compute next counter via MAX(counter) + 1 per type+typeNo │
 * │  2. INSERT into gl_trans table via repository                 │
 * │  3. Return $amount (same contract as FA core)                 │
 * └──────────────────────────────────────────────────────────────┘
 */
final class GlTransServiceStandard implements GlTransService
{
    private GlTransRepository $glTransRepo;

    public function __construct(GlTransRepository $glTransRepo)
    {
        $this->glTransRepo = $glTransRepo;
    }

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
        $counter = $this->glTransRepo->getNextCounter($type, $typeNo);

        $data = [
            'counter' => $counter,
            'type' => $type,
            'type_no' => $typeNo,
            'tran_date' => $tranDate,
            'account' => $account,
            'memo_' => $memo,
            'amount' => $amount,
            'dimension_id' => (int)$dimensionId,
            'dimension2_id' => (int)$dimension2Id,
        ];

        if ($personId !== 0) {
            $data['person_type_id'] = (int)$personType;
            $data['person_id'] = $personId;
        }

        $this->glTransRepo->insert($data);

        return $amount;
    }

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
        return $this->addGlTrans(
            $type, $typeNo, $tranDate, $account,
            $dimensionId, $dimension2Id, '',
            $amount, null,
            PT_CUSTOMER, $customerId
        );
    }
}
