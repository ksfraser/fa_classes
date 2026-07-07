<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\BankAccount;
use FrontAccounting\Repository\BankAccountsRepository;
use FrontAccounting\Tests\FakeDbAdapter;
use Ksfraser\Validation\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

final class BankAccountsRepositoryTest extends TestCase
{
    public function testFindByBankAccountNumberReturnsNullWhenNoRows(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new BankAccountsRepository($db, '0_bank_accounts');

        $result = $repo->findByBankAccountNumber('ABC');

        $this->assertNull($result);
        $this->assertSame('ABC', $db->lastParams['bank_account_number'] ?? null);
        $this->assertStringContainsString('FROM 0_bank_accounts', $db->lastSql ?? '');
        $this->assertStringContainsString('WHERE bank_account_number = :bank_account_number', $db->lastSql ?? '');
        $this->assertStringContainsString('LIMIT 1', $db->lastSql ?? '');
    }

    public function testFindByBankAccountNumberMapsRowToDtoWithCasts(): void
    {
        $db = new FakeDbAdapter([
            [
                'id' => '7',
                'bank_account_name' => 'Ops',
                'bank_account_number' => '999',
                'bank_curr_code' => 'USD',
                'inactive' => 1,
            ],
        ]);
        $repo = new BankAccountsRepository($db, '0_bank_accounts');

        $dto = $repo->findByBankAccountNumber('999');

        $this->assertInstanceOf(BankAccount::class, $dto);
        $this->assertSame(7, $dto->getId());
        $this->assertSame('Ops', $dto->getBankAccountName());
        $this->assertSame('999', $dto->getBankAccountNumber());
        $this->assertSame('USD', $dto->getBankCurrCode());
        $this->assertTrue($dto->isInactive());
    }

    public function testFindByBankAccountNumberRejectsEmpty(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new BankAccountsRepository($db, '0_bank_accounts');

        $this->expectException(ValidationException::class);
        $repo->findByBankAccountNumber('   ');
    }

    public function testFindByBankAccountNumberRejectsTooLong(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new BankAccountsRepository($db, '0_bank_accounts');

        $this->expectException(ValidationException::class);
        $repo->findByBankAccountNumber(str_repeat('a', 256));
    }
}
