<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\PurchaseOrder;
use PHPUnit\Framework\TestCase;

final class PurchaseOrderTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new PurchaseOrder(
            500, 5, 'Order comments', '2026-01-15', 'PO-001',
            'REQ-001', 'LOC', '123 Main St',
            1000.0, 100.0, 800.0, 0
        );

        $this->assertSame(500, $dto->getOrderNo());
        $this->assertSame(5, $dto->getSupplierId());
        $this->assertSame('Order comments', $dto->getComments());
        $this->assertSame('2026-01-15', $dto->getOrdDate());
        $this->assertSame('PO-001', $dto->getReference());
        $this->assertSame('REQ-001', $dto->getRequisitionNo());
        $this->assertSame('LOC', $dto->getIntoStockLocation());
        $this->assertSame('123 Main St', $dto->getDeliveryAddress());
        $this->assertSame(1000.0, $dto->getTotal());
        $this->assertSame(100.0, $dto->getPrepAmount());
        $this->assertSame(800.0, $dto->getAlloc());
        $this->assertSame(0, $dto->getTaxIncluded());
    }

    public function testNullComments(): void
    {
        $dto = new PurchaseOrder(1, 1, null, '2026-01-01', '', null, '', '', 0.0, 0.0, 0.0, 0);
        $this->assertNull($dto->getComments());
        $this->assertNull($dto->getRequisitionNo());
    }
}
