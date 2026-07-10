<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Service\Contracts\BankTransService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of BankTransService.
 *
 * Inserts/voids bank_trans rows directly via BankTransactionRepository
 * instead of delegating to FA core's add_bank_trans() / void_bank_trans().
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │               BankTransServiceStandard                    │
 * │  - bankTransRepo: BankTransactionRepository               │
 * ├──────────────────────────────────────────────────────────┤
 * │  + addBankTrans(...): bool                               │
 * │  + voidBankTrans($type, $transNo, $isEditing): void      │
 * └──────────────────────────────────────────────────────────┘
 */
final class BankTransServiceStandard implements BankTransService
{
    private BankTransactionRepository $bankTransRepo;

    public function __construct(BankTransactionRepository $bankTransRepo)
    {
        $this->bankTransRepo = $bankTransRepo;
    }

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
        $data = [
            'type' => $type,
            'trans_no' => $transNo,
            'bank_act' => $bankAccount,
            'ref' => $ref,
            'trans_date' => $date_,
            'amount' => $amount,
        ];

        if ($personType !== '' || $personId !== 0) {
            $data['person_type'] = $personType;
            $data['person_id'] = $personId;
        }

        $id = $this->bankTransRepo->insert($data);
        return $id > 0;
    }

    public function voidBankTrans(int $type, int $transNo, bool $isEditing = true): void
    {
        $rows = $this->bankTransRepo->findByTransaction($type, $transNo);
        foreach ($rows as $row) {
            $this->bankTransRepo->deleteWhere([
                'type' => $type,
                'trans_no' => $transNo,
                'id' => $row->getId(),
            ]);
        }
    }
}
