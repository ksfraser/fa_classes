<?php

namespace Ksfraser\FA\DTO;

/**
 * @deprecated since 0.2.0, use \FrontAccounting\DTO\BankAccount instead.
 * This stub will be removed in a future version.
 */
class BankAccount extends \FrontAccounting\DTO\BankAccount
{
    public function __construct(
        int $id,
        string $bankAccountName,
        string $bankAccountNumber,
        string $bankCurrCode,
        bool $inactive
    ) {
        trigger_error(
            __CLASS__ . ' is deprecated, use FrontAccounting\DTO\BankAccount instead.',
            E_USER_DEPRECATED
        );
        parent::__construct($id, $bankAccountName, $bankAccountNumber, $bankCurrCode, $inactive);
    }
}
