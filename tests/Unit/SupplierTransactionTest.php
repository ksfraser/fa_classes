<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\SupplierTransaction;
use PHPUnit\Framework\TestCase;

final class SupplierTransactionTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new SupplierTransaction(
            1001, 20, 5, 'REF001', 'SUPP-REF',
            '2026-01-15', '2026-02-14',
            1000.0, 50.0, 100.0, 1.0, 800.0, 0
        );

        $this->assertSame(1001, $dto->getTransNo());
        $this->assertSame(20, $dto->getType());
        $this->assertSame(5, $dto->getSupplierId());
        $this->assertSame('REF001', $dto->getReference());
        $this->assertSame('SUPP-REF', $dto->getSuppReference());
        $this->assertSame('2026-01-15', $dto->getTranDate());
        $this->assertSame('2026-02-14', $dto->getDueDate());
        $this->assertSame(1000.0, $dto->getOvAmount());
        $this->assertSame(50.0, $dto->getOvDiscount());
        $this->assertSame(100.0, $dto->getOvGst());
        $this->assertSame(1.0, $dto->getRate());
        $this->assertSame(800.0, $dto->getAlloc());
        $this->assertSame(0, $dto->getTaxIncluded());
    }

    public function testGetTotal(): void
    {
        $dto = new SupplierTransaction(
            1, 20, 1, '', '', '2026-01-01', '2026-02-01',
            1000.0, 50.0, 100.0, 1.0, 0.0, 0
        );
        $this->assertSame(1150.0, $dto->getTotal());
    }

    public function testGetBalance(): void
    {
        $dto = new SupplierTransaction(
            1, 20, 1, '', '', '2026-01-01', '2026-02-01',
            1000.0, 50.0, 100.0, 1.0, 500.0, 0
        );
        $this->assertSame(650.0, $dto->getBalance());
    }
}
