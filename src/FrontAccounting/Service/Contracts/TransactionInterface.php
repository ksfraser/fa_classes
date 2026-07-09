<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for database transaction operations.
 */
interface TransactionInterface
{
    public function begin(): void;

    public function commit(): void;
}
