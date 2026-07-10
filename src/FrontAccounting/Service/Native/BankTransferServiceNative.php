<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\BankTransferRequest;
use FrontAccounting\Service\Contracts\BankTransferService;

/**
 * @since 2026-07-10
 * Native implementation of BankTransferService wrapping FA core
 * add_bank_transfer() / update_bank_transfer().
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │              BankTransferServiceNative                    │
 * ├──────────────────────────────────────────────────────────┤
 * │  + addBankTransfer($request): int                        │
 * │  + updateBankTransfer($request): int                     │
 * └──────────────────────────────────────────────────────────┘
 */
class BankTransferServiceNative implements BankTransferService
{
    public function addBankTransfer(BankTransferRequest $request): int
    {
        return \add_bank_transfer(
            $request->getFromBankAccount(),
            $request->getToBankAccount(),
            $request->getTransDate(),
            $request->getAmount(),
            $request->getRef(),
            $request->getMemo(),
            $request->getCharge(),
            $request->getTargetAmount()
        );
    }

    public function updateBankTransfer(BankTransferRequest $request): int
    {
        return \update_bank_transfer(
            $request->getTransNo() ?? 0,
            $request->getFromBankAccount(),
            $request->getToBankAccount(),
            $request->getTransDate(),
            $request->getAmount(),
            $request->getRef(),
            $request->getMemo(),
            $request->getCharge(),
            $request->getTargetAmount()
        );
    }
}
