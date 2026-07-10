<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\BankAccountsRepository;
use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Service\Standard\BankAccountServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class BankAccountServiceStandardTest extends TestCase
{
    public function testGetBankAccountReturnsRow(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_account_name' => 'Cheque',
            'bank_account_number' => '1234',
            'bank_curr_code' => 'CAD',
            'inactive' => 0,
        ]]);
        $svc = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $result = $svc->getBankAccount(1);

        $this->assertIsArray($result);
        $this->assertSame('CAD', $result['bank_curr_code']);
    }

    public function testGetBankAccountReturnsNullWhenNotFound(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $this->assertNull($svc->getBankAccount(999));
    }

    public function testGetBankGlAccountReturnsInt(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_gl_account' => 1001,
        ]]);
        $svc = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $this->assertSame(1001, $svc->getBankGlAccount(1));
    }

    public function testGetBranchAccountsReturnsExpectedKeys(): void
    {
        $db = new FakeDbAdapter([[
            'branch_code' => 1,
            'debtor_no' => 1,
            'receivables_account' => '1100',
            'payment_discount_account' => '4205',
        ]]);
        $svc = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $result = $svc->getBranchAccounts(1);

        $this->assertSame('1100', $result['receivables_account']);
        $this->assertSame('4205', $result['payment_discount_account']);
    }

    public function testGetBankChargeAccountReturnsString(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_charge_act' => '4500',
        ]]);
        $svc = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $this->assertSame('4500', $svc->getBankChargeAccount(1));
    }
}
