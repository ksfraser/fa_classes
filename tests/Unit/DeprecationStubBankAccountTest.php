<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\BankAccount;
use PHPUnit\Framework\TestCase;

/**
 * Tests that the Ksfraser\FA\DTO\BankAccount stub extends
 * FrontAccounting\DTO\BankAccount and triggers a deprecation notice.
 */
final class DeprecationStubBankAccountTest extends TestCase
{
    public function testStubExtendsFrontAccountingBankAccount(): void
    {
        $caught = null;
        set_error_handler(function ($errno, $errstr) use (&$caught) {
            $caught = [$errno, $errstr];
        }, E_USER_DEPRECATED);

        $stub = new \Ksfraser\FA\DTO\BankAccount(1, 'Test', '123', 'USD', false);

        restore_error_handler();

        $this->assertNotNull($caught, 'Expected deprecation notice');
        $this->assertSame(E_USER_DEPRECATED, $caught[0]);
        $this->assertStringContainsString('deprecated', $caught[1]);
        $this->assertStringContainsString('FrontAccounting\DTO\BankAccount', $caught[1]);
        $this->assertInstanceOf(BankAccount::class, $stub);
        $this->assertSame(1, $stub->getId());
    }
}
