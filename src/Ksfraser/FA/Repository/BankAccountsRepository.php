<?php

namespace Ksfraser\FA\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

/**
 * @deprecated since 0.2.0, use \FrontAccounting\Repository\BankAccountsRepository instead.
 * This stub will be removed in a future version.
 */
class BankAccountsRepository extends \FrontAccounting\Repository\BankAccountsRepository
{
    public function __construct(DbAdapterInterface $db, string $tableName)
    {
        trigger_error(
            __CLASS__ . ' is deprecated, use FrontAccounting\Repository\BankAccountsRepository instead.',
            E_USER_DEPRECATED
        );
        parent::__construct($db, $tableName);
    }
}
