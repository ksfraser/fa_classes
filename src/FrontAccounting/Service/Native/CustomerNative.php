<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * @since 2026-07-09
 * Native wrapper for FA core customer query functions.
 *
 * Wraps get_customer_currency() and get_customer_habit()
 * from sales/includes/db/customers_db.inc.
 */
class CustomerNative
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
