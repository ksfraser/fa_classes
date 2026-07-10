<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

use FrontAccounting\Service\BankTransferRequest;

/**
 * @since 2026-07-10
 * Service contract for intra-bank transfer operations.
 *
 * A bank transfer moves funds from one bank account to another,
 * optionally with a charge and/or cross-currency target amount.
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │                   BankTransferService                     │
 * ├──────────────────────────────────────────────────────────┤
 * │  + addBankTransfer(BankTransferRequest): int             │
 * │  + updateBankTransfer(BankTransferRequest): int          │
 * └──────────────────────────────────────────────────────────┘
 */
interface BankTransferService
{
    /**
     * Create a new bank transfer.
     *
     * @return int  The new transaction number
     */
    public function addBankTransfer(BankTransferRequest $request): int;

    /**
     * Update an existing bank transfer.
     *
     * @return int  The transaction number
     */
    public function updateBankTransfer(BankTransferRequest $request): int;
}
