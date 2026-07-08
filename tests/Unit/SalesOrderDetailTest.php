<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SalesOrderDetail;
use PHPUnit\Framework\TestCase;

final class SalesOrderDetailTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new SalesOrderDetail(1, 100, 30, 'ITEM01', 'Test', 2.0, 25.0, 10.0, 8.0, 5.0);

        $this->assertSame(1, $dto->getId());
        $this->assertSame(100, $dto->getOrderNo());
        $this->assertSame(30, $dto->getTransType());
        $this->assertSame('ITEM01', $dto->getStkCode());
        $this->assertSame('Test', $dto->getDescription());
        $this->assertSame(2.0, $dto->getQtySent());
        $this->assertSame(25.0, $dto->getUnitPrice());
        $this->assertSame(10.0, $dto->getQuantity());
        $this->assertSame(8.0, $dto->getInvoiced());
        $this->assertSame(5.0, $dto->getDiscountPercent());
    }
}
