<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\CustomerInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core customer query functions.
 */
class CustomerNative implements CustomerInterface
{
    /**
     * Wrap get_customer_currency().
     */
    public function getCustomerCurrency(int $customerId): string
    {
        return \get_customer_currency($customerId);
    }

    /**
     * Wrap get_customer_habit().
     *
     * @return array<string, mixed>
     */
    public function getCustomerHabit(int $customerId): array
    {
        return \get_customer_habit($customerId);
    }
}
