<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\CrmCategory;
use PHPUnit\Framework\TestCase;

final class CrmCategoryTest extends TestCase
{
    public function testGettersExposeConstructorValues(): void
    {
        $dto = new CrmCategory(1, 'customer', 'general', 'General', 'General contact', 1, 0);

        $this->assertSame(1, $dto->getId());
        $this->assertSame('customer', $dto->getType());
        $this->assertSame('general', $dto->getAction());
        $this->assertSame('General', $dto->getName());
        $this->assertSame('General contact', $dto->getDescription());
        $this->assertSame(1, $dto->getSystem());
        $this->assertSame(0, $dto->getInactive());
    }
}
