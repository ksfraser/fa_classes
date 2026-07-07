<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\PurchOrderDetail;
use PHPUnit\Framework\TestCase;

final class PurchOrderDetailTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new PurchOrderDetail(1, 100, 'ITEM01', 10.0, 5.0, 3.0);

        $this->assertSame(1, $dto->getPoDetailItem());
        $this->assertSame(100, $dto->getOrderNo());
        $this->assertSame('ITEM01', $dto->getItemCode());
        $this->assertSame(10.0, $dto->getQuantityOrdered());
        $this->assertSame(5.0, $dto->getQuantityReceived());
        $this->assertSame(3.0, $dto->getQtyInvoiced());
    }
}
