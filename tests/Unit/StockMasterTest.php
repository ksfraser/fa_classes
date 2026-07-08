<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\StockMaster;
use PHPUnit\Framework\TestCase;

final class StockMasterTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new StockMaster(
            'ITEM01', 1, 2, 'Test Item', 'Long desc', 'each', 'B',
            '1000', '2000', '3000', '4000', '5000',
            0, 0, 10.0, 5.0, 3.0, 2.0,
            0, 0, 0, 1
        );

        $this->assertSame('ITEM01', $dto->getStockId());
        $this->assertSame(1, $dto->getCategoryId());
        $this->assertSame(2, $dto->getTaxTypeId());
        $this->assertSame('Test Item', $dto->getDescription());
        $this->assertSame('Long desc', $dto->getLongDescription());
        $this->assertSame('each', $dto->getUnits());
        $this->assertSame('B', $dto->getMbFlag());
        $this->assertSame('1000', $dto->getSalesAccount());
        $this->assertSame('2000', $dto->getCogsAccount());
        $this->assertSame('4000', $dto->getAdjustmentAccount());
        $this->assertSame('5000', $dto->getWipAccount());
        $this->assertSame(10.0, $dto->getPurchaseCost());
        $this->assertSame(5.0, $dto->getMaterialCost());
        $this->assertSame(3.0, $dto->getLabourCost());
        $this->assertSame(2.0, $dto->getOverheadCost());
        $this->assertSame(1, $dto->getEditable());
        $this->assertSame(0, $dto->getDimensionId());
        $this->assertSame(0, $dto->getDimension2Id());
    }

    public function testWithDimensionIds(): void
    {
        $dto = new StockMaster(
            'ITEM02', 1, 1, '', '', '', 'B',
            '', '', '', '', '', 5, 10,
            0.0, 0.0, 0.0, 0.0, 0, 0, 0, 0
        );

        $this->assertSame(5, $dto->getDimensionId());
        $this->assertSame(10, $dto->getDimension2Id());
    }
}
