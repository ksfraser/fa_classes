<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\PurchaseOrderDetail;
use PHPUnit\Framework\TestCase;

final class PurchaseOrderDetailTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new PurchaseOrderDetail(
            10, 500, 'ITEM01', 'Test item', '2026-02-15',
            5.0, 10.0, 9.5, 8.0, 10.0, 3.0
        );

        $this->assertSame(10, $dto->getPoDetailItem());
        $this->assertSame(500, $dto->getOrderNo());
        $this->assertSame('ITEM01', $dto->getItemCode());
        $this->assertSame('Test item', $dto->getDescription());
        $this->assertSame('2026-02-15', $dto->getDeliveryDate());
        $this->assertSame(5.0, $dto->getQtyInvoiced());
        $this->assertSame(10.0, $dto->getUnitPrice());
        $this->assertSame(9.5, $dto->getActPrice());
        $this->assertSame(8.0, $dto->getStdCostUnit());
        $this->assertSame(10.0, $dto->getQuantityOrdered());
        $this->assertSame(3.0, $dto->getQuantityReceived());
    }
}
