<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Repository\BankAccountsRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class DeprecationStubBankAccountsRepositoryTest extends TestCase
{
    public function testStubExtendsFrontAccountingBankAccountsRepository(): void
    {
        $caught = null;
        set_error_handler(function ($errno, $errstr) use (&$caught) {
            $caught = [$errno, $errstr];
        }, E_USER_DEPRECATED);

        $db = new FakeDbAdapter([]);
        $stub = new \Ksfraser\FA\Repository\BankAccountsRepository($db, '0_bank_accounts');

        restore_error_handler();

        $this->assertNotNull($caught, 'Expected deprecation notice');
        $this->assertSame(E_USER_DEPRECATED, $caught[0]);
        $this->assertStringContainsString('deprecated', $caught[1]);
        $this->assertStringContainsString('FrontAccounting\Repository\BankAccountsRepository', $caught[1]);
        $this->assertInstanceOf(BankAccountsRepository::class, $stub);
    }
}
