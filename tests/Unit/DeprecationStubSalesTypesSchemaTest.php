<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\Schema\SalesTypesSchema;
use PHPUnit\Framework\TestCase;

final class DeprecationStubSalesTypesSchemaTest extends TestCase
{
    public function testStubExtendsFrontAccountingSalesTypesSchema(): void
    {
        $caught = null;
        set_error_handler(function ($errno, $errstr) use (&$caught) {
            $caught = [$errno, $errstr];
        }, E_USER_DEPRECATED);

        $result = \Ksfraser\FA\Schema\SalesTypesSchema::descriptor();

        restore_error_handler();

        $this->assertNotNull($caught, 'Expected deprecation notice');
        $this->assertSame(E_USER_DEPRECATED, $caught[0]);
        $this->assertStringContainsString('deprecated', $caught[1]);
        $this->assertStringContainsString('FrontAccounting\Schema\SalesTypesSchema', $caught[1]);
        $this->assertSame('sales_types', $result['entity']);
    }
}
