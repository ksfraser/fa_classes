<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\TransactionService;

/**
 * @since 2026-07-09
 * Native implementation of TransactionService wrapping FA core transaction functions.
 */
class TransactionServiceNative implements TransactionService
{
    /**
     * Wrap begin_transaction().
     */
    public function begin(): void
    {
        \begin_transaction();
    }

    /**
     * Wrap commit_transaction().
     */
    public function commit(): void
    {
        \commit_transaction();
    }
}
