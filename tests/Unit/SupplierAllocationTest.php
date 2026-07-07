<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SupplierAllocation;
use PHPUnit\Framework\TestCase;

final class SupplierAllocationTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new SupplierAllocation(200.50, 22, 5, 20, 10, 3, '2025-07-01');

        $this->assertSame(200.50, $dto->getAmount());
        $this->assertSame(22, $dto->getTransTypeFrom());
        $this->assertSame(5, $dto->getTransNoFrom());
        $this->assertSame(20, $dto->getTransTypeTo());
        $this->assertSame(10, $dto->getTransNoTo());
        $this->assertSame(3, $dto->getPersonId());
        $this->assertSame('2025-07-01', $dto->getDateAlloc());
    }
}
