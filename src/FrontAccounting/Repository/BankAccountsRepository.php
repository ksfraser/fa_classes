<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\BankAccount;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;
use Ksfraser\Validation\Traits\ValidatesStringTrait;

class BankAccountsRepository
{
    use RepositoryTrait;
    use ValidatesStringTrait;

    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    protected function getTableName(): string
    {
        return 'bank_accounts';
    }

    public function findByBankAccountNumber(string $bankAccountNumber): ?BankAccount
    {
        $this->assertNotEmptyString($bankAccountNumber, 'bankAccountNumber');
        $this->assertStringMaxLen($bankAccountNumber, 255, 'bankAccountNumber');

        $sql = "SELECT id, bank_account_name, bank_account_number, bank_curr_code, inactive "
            . "FROM {$this->prefix}bank_accounts "
            . "WHERE bank_account_number = ? "
            . "LIMIT 1";

        $rows = $this->db->query($sql, [$bankAccountNumber]);
        if (count($rows) < 1) {
            return null;
        }

        $row = $rows[0];
        return new BankAccount(
            (int)($row['id'] ?? 0),
            (string)($row['bank_account_name'] ?? ''),
            (string)($row['bank_account_number'] ?? ''),
            (string)($row['bank_curr_code'] ?? ''),
            (bool)($row['inactive'] ?? false)
        );
    }
}
