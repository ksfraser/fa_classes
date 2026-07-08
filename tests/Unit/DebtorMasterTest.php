<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\DebtorMaster;
use PHPUnit\Framework\TestCase;

final class DebtorMasterTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new DebtorMaster(3, 'Acme Corp', 'ACME001', '123 Main St', 'TAX-001', 'USD', 1, 0, 0, 0, 30, 2.5, 1.0, 10000.0, 'Notes', 0);

        $this->assertSame(3, $dto->getDebtorNo());
        $this->assertSame('Acme Corp', $dto->getName());
        $this->assertSame('ACME001', $dto->getDebtorRef());
        $this->assertSame('123 Main St', $dto->getAddress());
        $this->assertSame('TAX-001', $dto->getTaxId());
        $this->assertSame('USD', $dto->getCurrCode());
        $this->assertSame(1, $dto->getSalesType());
        $this->assertSame(30, $dto->getPaymentTerms());
        $this->assertSame(2.5, $dto->getDiscount());
        $this->assertSame(1.0, $dto->getPymtDiscount());
        $this->assertSame(10000.0, $dto->getCreditLimit());
    }

    public function testNullPaymentTerms(): void
    {
        $dto = new DebtorMaster(1, '', '', null, '', '', 0, 0, 0, 0, null, 0.0, 0.0, 0.0, '', 0);
        $this->assertNull($dto->getPaymentTerms());
        $this->assertNull($dto->getAddress());
    }
}
