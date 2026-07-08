<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SalesOrder;
use PHPUnit\Framework\TestCase;

final class SalesOrderTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new SalesOrder(
            100, 30, 0, 0, 3, 1, 'SO-001', 'CUST-REF', 'Order notes',
            '2026-01-15', 1, 2, '456 Oak Ave', '555-0100', 'a@b.com',
            'John Doe', 15.0, 'LOC', '2026-02-15', 1, 500.0, 0.0, 300.0
        );

        $this->assertSame(100, $dto->getOrderNo());
        $this->assertSame(30, $dto->getTransType());
        $this->assertSame(3, $dto->getDebtorNo());
        $this->assertSame(1, $dto->getBranchCode());
        $this->assertSame('SO-001', $dto->getReference());
        $this->assertSame('CUST-REF', $dto->getCustomerRef());
        $this->assertSame('Order notes', $dto->getComments());
        $this->assertSame(1, $dto->getOrderType());
        $this->assertSame(2, $dto->getShipVia());
        $this->assertSame('555-0100', $dto->getContactPhone());
        $this->assertSame('a@b.com', $dto->getContactEmail());
        $this->assertSame('John Doe', $dto->getDeliverTo());
        $this->assertSame(15.0, $dto->getFreightCost());
        $this->assertSame(1, $dto->getPaymentTerms());
        $this->assertSame(500.0, $dto->getTotal());
        $this->assertSame(300.0, $dto->getAlloc());
    }

    public function testNullOptionals(): void
    {
        $dto = new SalesOrder(
            1, 30, 0, 0, 1, 0, '', '', null,
            '2026-01-01', 0, 0, '', null, null, '',
            0.0, '', '2026-01-01', null, 0.0, 0.0, 0.0
        );
        $this->assertNull($dto->getComments());
        $this->assertNull($dto->getContactPhone());
        $this->assertNull($dto->getContactEmail());
        $this->assertNull($dto->getPaymentTerms());
    }
}
