<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Service\Contracts\TransactionService;

/**
 * @since 2026-07-10
 * Standard no-op implementation of TransactionService.
 *
 * Database transactions are already handled by the DbAdapter. In a
 * DTO/Repository context there is no need for begin/commit wrappers.
 *
 * ┌────────────────────────────────────────────────────┐
 * │            TransactionServiceStandard               │
 * ├────────────────────────────────────────────────────┤
 * │  + begin(): void  — no-op                          │
 * │  + commit(): void — no-op                          │
 * └────────────────────────────────────────────────────┘
 */
final class TransactionServiceStandard implements TransactionService
{
    public function begin(): void
    {
        // no-op — transactions managed by DbAdapter
    }

    public function commit(): void
    {
        // no-op — transactions managed by DbAdapter
    }
}
