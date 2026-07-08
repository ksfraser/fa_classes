<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\DebtorTransactionDetail;
use PHPUnit\Framework\TestCase;

final class DebtorTransactionDetailTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new DebtorTransactionDetail(
            1, 2001, 10, 'ITEM01', 'Test Item',
            15.0, 1.5, 3.0, 5.0, 10.0, 2.0, 100
        );

        $this->assertSame(1, $dto->getId());
        $this->assertSame(2001, $dto->getDebtorTransNo());
        $this->assertSame(10, $dto->getDebtorTransType());
        $this->assertSame('ITEM01', $dto->getStockId());
        $this->assertSame(15.0, $dto->getUnitPrice());
        $this->assertSame(1.5, $dto->getUnitTax());
        $this->assertSame(3.0, $dto->getQuantity());
        $this->assertSame(5.0, $dto->getDiscountPercent());
        $this->assertSame(10.0, $dto->getStandardCost());
        $this->assertSame(2.0, $dto->getQtyDone());
        $this->assertSame(100, $dto->getSrcId());
    }
}
