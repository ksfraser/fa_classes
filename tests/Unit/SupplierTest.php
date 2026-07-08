<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\Supplier;
use PHPUnit\Framework\TestCase;

final class SupplierTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new Supplier(5, 'Supplier Co', 'SUP001', '123 Main', '456 Invoice', 'GST-001', 'John', 'ACC-001', 'www.sup.com', 'BNK-001', 'USD', 30, 0, 0, 0, 1, 50000.0, '5000', '6000', '7000', 'Notes', 0);

        $this->assertSame(5, $dto->getSupplierId());
        $this->assertSame('Supplier Co', $dto->getSuppName());
        $this->assertSame('SUP001', $dto->getSuppRef());
        $this->assertSame('123 Main', $dto->getAddress());
        $this->assertSame('456 Invoice', $dto->getSuppAddress());
        $this->assertSame('GST-001', $dto->getGstNo());
        $this->assertSame('John', $dto->getContact());
        $this->assertSame('ACC-001', $dto->getSuppAccountNo());
        $this->assertSame('www.sup.com', $dto->getWebsite());
        $this->assertSame('BNK-001', $dto->getBankAccount());
        $this->assertSame('USD', $dto->getCurrCode());
        $this->assertSame(30, $dto->getPaymentTerms());
        $this->assertSame(1, $dto->getTaxGroupId());
        $this->assertSame(50000.0, $dto->getCreditLimit());
    }

    public function testNullOptionals(): void
    {
        $dto = new Supplier(1, '', '', '', '', '', '', '', '', '', null, null, 0, 0, 0, null, 0.0, '', '', '', '', 0);
        $this->assertNull($dto->getCurrCode());
        $this->assertNull($dto->getPaymentTerms());
        $this->assertNull($dto->getTaxGroupId());
    }
}
