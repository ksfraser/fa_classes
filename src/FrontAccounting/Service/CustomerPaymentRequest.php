<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

/**
 * Immutable DTO for customer payment creation parameters.
 *
 * Corresponds to the parameter list of FA core write_customer_payment().
 */
final class CustomerPaymentRequest
{
    public function __construct(
        public readonly int $transNo,
        public readonly int $customerId,
        public readonly int $branchId,
        public readonly int $bankAccount,
        public readonly string $date,
        public readonly string $ref,
        public readonly float $amount,
        public readonly float $discount = 0.0,
        public readonly string $memo = '',
        public readonly float $rate = 0.0,
        public readonly float $charge = 0.0,
        public readonly float $bankAmount = 0.0,
    ) {
    }
}
