<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\DebtorTransaction;
use PHPUnit\Framework\TestCase;

final class DebtorTransactionTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new DebtorTransaction(
            2001, 10, 3, 1, '2026-01-15', '2026-02-14', 'INV-001',
            500, 1500.0, 150.0, 50.0, 5.0, 30.0, 1200.0, 0.0,
            1.0, 2, 0, 0, 1, 0
        );

        $this->assertSame(2001, $dto->getTransNo());
        $this->assertSame(10, $dto->getType());
        $this->assertSame(3, $dto->getDebtorNo());
        $this->assertSame(1, $dto->getBranchCode());
        $this->assertSame('INV-001', $dto->getReference());
        $this->assertSame(500, $dto->getOrder_());
        $this->assertSame(1500.0, $dto->getOvAmount());
        $this->assertSame(150.0, $dto->getOvGst());
        $this->assertSame(50.0, $dto->getOvFreight());
        $this->assertSame(5.0, $dto->getOvFreightTax());
        $this->assertSame(30.0, $dto->getOvDiscount());
        $this->assertSame(1200.0, $dto->getAlloc());
        $this->assertSame(2, $dto->getShipVia());
    }

    public function testGetTotal(): void
    {
        $dto = new DebtorTransaction(
            1, 10, 1, 0, '2026-01-01', '2026-02-01', '', 0,
            1000.0, 100.0, 50.0, 5.0, 25.0, 0.0, 0.0, 1.0,
            0, 0, 0, 0, 0
        );
        $this->assertSame(1180.0, $dto->getTotal());
    }

    public function testGetBalance(): void
    {
        $dto = new DebtorTransaction(
            1, 10, 1, 0, '2026-01-01', '2026-02-01', '', 0,
            1000.0, 100.0, 50.0, 5.0, 25.0, 600.0, 0.0, 1.0,
            0, 0, 0, 0, 0
        );
        $this->assertSame(580.0, $dto->getBalance());
    }
}
