<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\BankAccountsRepository;
use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Service\Native\BankAccountServiceNative;
use FrontAccounting\Service\Standard\BankAccountServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class BankAccountServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testGetBankAccountBothReturnExpectedCurrency(): void
    {
        // famock: get_bank_account(1) returns USD
        $native = new BankAccountServiceNative();

        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_account_name' => 'Test Bank Account 1',
            'bank_account_number' => '1234',
            'bank_curr_code' => 'USD',
            'bank_name' => 'Test Bank',
            'account_code' => '1001',
            'inactive' => 0,
        ]]);
        $standard = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $nativeResult = $native->getBankAccount(1);
        $standardResult = $standard->getBankAccount(1);

        $this->assertIsArray($nativeResult);
        $this->assertIsArray($standardResult);
        $this->assertSame($nativeResult['bank_curr_code'] ?? null, $standardResult['bank_curr_code'] ?? null);
    }

    public function testGetBankGlAccountBothReturnInt(): void
    {
        // famock: get_bank_gl_account(1) returns 1001
        $native = new BankAccountServiceNative();
        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_gl_account' => 1001,
        ]]);
        $standard = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $nativeResult = $native->getBankGlAccount(1);
        $standardResult = $standard->getBankGlAccount(1);

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testGetBranchAccountsBothReturnSameReceivables(): void
    {
        // famock: get_branch_accounts(1) returns receivables_account => '1100'
        $native = new BankAccountServiceNative();
        $db = new FakeDbAdapter([[
            'branch_code' => 1,
            'debtor_no' => 1,
            'receivables_account' => '1100',
            'payment_discount_account' => '4205',
        ]]);
        $standard = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $nativeResult = $native->getBranchAccounts(1);
        $standardResult = $standard->getBranchAccounts(1);

        $this->assertSame(
            $nativeResult['receivables_account'],
            $standardResult['receivables_account']
        );
        $this->assertSame(
            $nativeResult['payment_discount_account'],
            $standardResult['payment_discount_account']
        );
    }

    public function testGetBankChargeAccountBothReturnString(): void
    {
        // famock: get_bank_charge_account(1) returns '4500'
        $native = new BankAccountServiceNative();
        $db = new FakeDbAdapter([[
            'id' => 1,
            'bank_charge_act' => '4500',
        ]]);
        $standard = new BankAccountServiceStandard(
            new BankAccountsRepository($db),
            new CustomerBranchRepository($db)
        );

        $nativeResult = $native->getBankChargeAccount(1);
        $standardResult = $standard->getBankChargeAccount(1);

        $this->assertSame($nativeResult, $standardResult);
    }
}
