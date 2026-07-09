<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * @since 2026-07-09
 * Native wrapper for FA core database transaction functions.
 *
 * Wraps begin_transaction() and commit_transaction() from
 * includes/db/connect_db.inc.
 */
class TransactionNative
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
