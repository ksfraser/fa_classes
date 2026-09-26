<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

/**
 * Immutable DTO for customer payment creation parameters.
 *
 * Corresponds to the parameter list of FA core write_customer_payment().
 *
 * Written to the PHP 7.4 floor: explicit public properties assigned in the
 * constructor rather than readonly promoted constructor properties, which are
 * PHP 8.1+. Properties stay public because CustomerPaymentService reads them
 * directly, and there are no setters, so instances are immutable in use.
 */
final class CustomerPaymentRequest
{
    /** @var int Existing payment to void and replace, or 0 for a new payment */
    public $transNo;

    /** @var int */
    public $customerId;

    /** @var int */
    public $branchId;

    /** @var int FA bank account id the payment is deposited into */
    public $bankAccount;

    /** @var string Transaction date, YYYY-MM-DD */
    public $date;

    /** @var string Payment reference */
    public $ref;

    /** @var float */
    public $amount;

    /** @var float */
    public $discount = 0.0;

    /** @var string */
    public $memo = '';

    /** @var float Exchange rate, 0.0 to derive from the customer's currency */
    public $rate = 0.0;

    /** @var float Bank charge */
    public $charge = 0.0;

    /** @var float Amount in bank currency, 0.0 to derive from amount and rate */
    public $bankAmount = 0.0;

    public function __construct(
        int $transNo,
        int $customerId,
        int $branchId,
        int $bankAccount,
        string $date,
        string $ref,
        float $amount,
        float $discount = 0.0,
        string $memo = '',
        float $rate = 0.0,
        float $charge = 0.0,
        float $bankAmount = 0.0
    ) {
        $this->transNo = $transNo;
        $this->customerId = $customerId;
        $this->branchId = $branchId;
        $this->bankAccount = $bankAccount;
        $this->date = $date;
        $this->ref = $ref;
        $this->amount = $amount;
        $this->discount = $discount;
        $this->memo = $memo;
        $this->rate = $rate;
        $this->charge = $charge;
        $this->bankAmount = $bankAmount;
    }
}
