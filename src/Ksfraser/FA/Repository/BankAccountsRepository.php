<?php

namespace Ksfraser\FA\Repository;

use Ksfraser\FA\DTO\BankAccount;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;
use Ksfraser\Validation\Traits\ValidatesStringTrait;

final class BankAccountsRepository
{
    use ValidatesStringTrait;

    /** @var DbAdapterInterface */
    private $db;
    /** @var string */
    private $tableName;

    public function __construct(DbAdapterInterface $db, string $tableName)
    {
        $this->db = $db;
        $this->tableName = $tableName;
    }

    public function findByBankAccountNumber(string $bankAccountNumber): ?BankAccount
    {
        $this->assertNotEmptyString($bankAccountNumber, 'bankAccountNumber');
        $this->assertStringMaxLen($bankAccountNumber, 255, 'bankAccountNumber');

        $sql = "SELECT id, bank_account_name, bank_account_number, bank_curr_code, inactive "
            . "FROM {$this->tableName} "
            . "WHERE bank_account_number = :bank_account_number "
            . "LIMIT 1";

        $rows = $this->db->query($sql, [
            'bank_account_number' => $bankAccountNumber,
        ]);
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

