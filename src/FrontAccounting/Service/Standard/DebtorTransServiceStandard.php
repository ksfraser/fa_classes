<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\DebtorTransactionRepository;
use FrontAccounting\Service\Contracts\DebtorTransService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of DebtorTransService.
 *
 * Inserts/queries debtor_trans rows directly via DebtorTransactionRepository
 * instead of delegating to FA core's write_customer_trans() / get_customer_trans().
 *
 * ┌───────────────────────────────────────────────────────────┐
 * │               DebtorTransServiceStandard                   │
 * │  - debtorTransRepo: DebtorTransactionRepository            │
 * ├───────────────────────────────────────────────────────────┤
 * │  + writeCustomerTrans(...): int                           │
 * │  + getCustomerTrans($transNo, $transType): array          │
 * └───────────────────────────────────────────────────────────┘
 */
final class DebtorTransServiceStandard implements DebtorTransService
{
    private DebtorTransactionRepository $debtorTransRepo;

    public function __construct(DebtorTransactionRepository $debtorTransRepo)
    {
        $this->debtorTransRepo = $debtorTransRepo;
    }

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
        if ($transNo === 0) {
            $transNo = $this->getNextTransNo($transType);
        }

        $this->debtorTransRepo->insert([
            'trans_no' => $transNo,
            'type' => $transType,
            'debtor_no' => $customerId,
            'branch_code' => $branchId,
            'tran_date' => $date_,
            'due_date' => $date_,
            'reference' => $ref,
            'order_' => 0,
            'ov_amount' => $amount,
            'ov_discount' => $discount,
            'ov_gst' => 0,
            'ov_freight' => 0,
            'ov_freight_tax' => 0,
            'alloc' => 0,
            'prep_amount' => 0,
            'rate' => 1.0,
            'ship_via' => 0,
            'dimension_id' => 0,
            'dimension2_id' => 0,
            'payment_terms' => 0,
            'tax_included' => 0,
        ]);

        return $transNo;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCustomerTrans(int $transNo, int $transType): array
    {
        $row = $this->debtorTransRepo->findOneWhere([
            'trans_no' => $transNo,
            'type' => $transType,
        ]);
        return $row ?? [];
    }

    private function getNextTransNo(int $type): int
    {
        return $this->debtorTransRepo->getNextTransNo($type);
    }
}
