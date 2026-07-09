<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\TransactionInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core database transaction functions.
 */
class TransactionNative implements TransactionInterface
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
