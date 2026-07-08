<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SupplierInvoiceItem;
use PHPUnit\Framework\TestCase;

final class SupplierInvoiceItemTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new SupplierInvoiceItem(
            1, 20, 1001, 'ITEM01', 'Test Item',
            10.0, 1.0, 5.0, 10, 50, 'memo text', 0, 0
        );

        $this->assertSame(1, $dto->getId());
        $this->assertSame(20, $dto->getSuppTransType());
        $this->assertSame(1001, $dto->getSuppTransNo());
        $this->assertSame('ITEM01', $dto->getStockId());
        $this->assertSame('Test Item', $dto->getDescription());
        $this->assertSame(10.0, $dto->getUnitPrice());
        $this->assertSame(1.0, $dto->getUnitTax());
        $this->assertSame(5.0, $dto->getQuantity());
        $this->assertSame(10, $dto->getGrnItemId());
        $this->assertSame(50, $dto->getPoDetailItemId());
        $this->assertSame('memo text', $dto->getMemo());
    }
}
