<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for customer query operations.
 */
interface CustomerInterface
{
    public function getCustomerCurrency(int $customerId): string;

    /**
     * @return array<string, mixed>
     */
    public function getCustomerHabit(int $customerId): array;
}
