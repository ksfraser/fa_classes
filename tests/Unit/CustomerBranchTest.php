<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\CustomerBranch;
use PHPUnit\Framework\TestCase;

final class CustomerBranchTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new CustomerBranch(1, 3, 'Main Branch', 'BR-001', '123 Main St', 2, 5, 'LOC', 1, '1000', '2000', '3000', '4000', 1, 'PO Box 123', 0, 'Notes', 'BNK-001', 0);

        $this->assertSame(1, $dto->getBranchCode());
        $this->assertSame(3, $dto->getDebtorNo());
        $this->assertSame('Main Branch', $dto->getBrName());
        $this->assertSame('BR-001', $dto->getBranchRef());
        $this->assertSame(2, $dto->getArea());
        $this->assertSame(5, $dto->getSalesman());
        $this->assertSame('LOC', $dto->getDefaultLocation());
        $this->assertSame(1, $dto->getTaxGroupId());
        $this->assertSame('1000', $dto->getSalesAccount());
        $this->assertSame('BNK-001', $dto->getBankAccount());
    }

    public function testNullOptionals(): void
    {
        $dto = new CustomerBranch(1, 1, '', '', '', null, 0, '', null, '', '', '', '', 0, '', 0, '', null, 0);
        $this->assertNull($dto->getArea());
        $this->assertNull($dto->getTaxGroupId());
        $this->assertNull($dto->getBankAccount());
    }
}
