<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\CrmContact;
use PHPUnit\Framework\TestCase;

final class CrmContactTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new CrmContact(1, 5, 'customer', 'general', '3');

        $this->assertSame(1, $dto->getId());
        $this->assertSame(5, $dto->getPersonId());
        $this->assertSame('customer', $dto->getType());
        $this->assertSame('general', $dto->getAction());
        $this->assertSame('3', $dto->getEntityId());
    }

    public function testWithNullEntityId(): void
    {
        $dto = new CrmContact(2, 6, 'supplier', 'billing', null);

        $this->assertNull($dto->getEntityId());
    }
}
