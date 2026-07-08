<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\CrmPerson;
use PHPUnit\Framework\TestCase;

final class CrmPersonTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new CrmPerson(1, 'P001', 'John Doe', null, '123 Main St', '555-0100', null, null, 'john@test.com', 'en', 'Notes', 0);

        $this->assertSame(1, $dto->getId());
        $this->assertSame('P001', $dto->getRef());
        $this->assertSame('John Doe', $dto->getName());
        $this->assertNull($dto->getName2());
        $this->assertSame('123 Main St', $dto->getAddress());
        $this->assertSame('555-0100', $dto->getPhone());
        $this->assertNull($dto->getPhone2());
        $this->assertNull($dto->getFax());
        $this->assertSame('john@test.com', $dto->getEmail());
        $this->assertSame('en', $dto->getLang());
        $this->assertSame('Notes', $dto->getNotes());
        $this->assertSame(0, $dto->getInactive());
    }

    public function testWithAllFields(): void
    {
        $dto = new CrmPerson(2, 'P002', 'Jane Doe', 'Smith', '456 Oak', '555-0200', '555-0201', '555-0202', 'jane@test.com', 'fr', 'Notes', 1);

        $this->assertSame('Jane Doe', $dto->getName());
        $this->assertSame('Smith', $dto->getName2());
        $this->assertSame('555-0201', $dto->getPhone2());
        $this->assertSame('555-0202', $dto->getFax());
        $this->assertSame(1, $dto->getInactive());
    }
}
