<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\StockMove;
use PHPUnit\Framework\TestCase;

final class StockMoveTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new StockMove(1, 1001, 'ITEM01', 20, 'LOC', '2026-01-15', 50.0, 'REF001', 10.0, 40.0);

        $this->assertSame(1, $dto->getTransId());
        $this->assertSame(1001, $dto->getTransNo());
        $this->assertSame('ITEM01', $dto->getStockId());
        $this->assertSame(20, $dto->getType());
        $this->assertSame('LOC', $dto->getLocCode());
        $this->assertSame('2026-01-15', $dto->getTranDate());
        $this->assertSame(50.0, $dto->getPrice());
        $this->assertSame('REF001', $dto->getReference());
        $this->assertSame(10.0, $dto->getQty());
        $this->assertSame(40.0, $dto->getStandardCost());
    }
}
