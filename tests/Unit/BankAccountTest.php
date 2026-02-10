<?php
declare(strict_types=1);

namespace Ksfraser\FA\Tests\Unit;

use Ksfraser\FA\DTO\BankAccount;
use PHPUnit\Framework\TestCase;

final class BankAccountTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new BankAccount(5, 'Main', '123', 'CAD', true);

        $this->assertSame(5, $dto->getId());
        $this->assertSame('Main', $dto->getBankAccountName());
        $this->assertSame('123', $dto->getBankAccountNumber());
        $this->assertSame('CAD', $dto->getBankCurrCode());
        $this->assertTrue($dto->isInactive());
    }
}
