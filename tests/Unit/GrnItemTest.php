<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\GrnItem;
use PHPUnit\Framework\TestCase;

final class GrnItemTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new GrnItem(1, 10, 100, 'ITEM01', 'Test Item', 5.0, 3.0);

        $this->assertSame(1, $dto->getId());
        $this->assertSame(10, $dto->getGrnBatchId());
        $this->assertSame(100, $dto->getPoDetailItem());
        $this->assertSame('ITEM01', $dto->getItemCode());
        $this->assertSame('Test Item', $dto->getDescription());
        $this->assertSame(5.0, $dto->getQtyRecd());
        $this->assertSame(3.0, $dto->getQtyInv());
    }
}
