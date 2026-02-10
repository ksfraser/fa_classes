<?php
declare(strict_types=1);

namespace Ksfraser\FA\Tests\Unit;

use Ksfraser\FA\Schema\SalesTypesSchema;
use PHPUnit\Framework\TestCase;

final class SalesTypesSchemaTest extends TestCase
{
    public function testDescriptorHasStableShapeAndIsMemoized(): void
    {
        $a = SalesTypesSchema::descriptor();
        $this->assertIsArray($a);
        $this->assertSame('sales_types', $a['entity']);
        $this->assertSame('sales_types', $a['table']);
        $this->assertSame('id', $a['primaryKey']);

        $this->assertArrayHasKey('fields', $a);
        $this->assertIsArray($a['fields']);
        $this->assertArrayHasKey('id', $a['fields']);
        $this->assertSame('int(11)', $a['fields']['id']['type']);
        $this->assertTrue($a['fields']['id']['auto_increment']);

        $this->assertArrayHasKey('ui', $a);
        $this->assertSame('Sales Types', $a['ui']['title']);
        $this->assertSame(['id', 'sales_type', 'tax_included', 'factor', 'inactive'], $a['ui']['listColumns']);
        $this->assertCount(2, $a['ui']['tabs']);
        $this->assertSame('List', $a['ui']['tabs'][0]['title']);

        $b = SalesTypesSchema::descriptor();
        $this->assertSame($a, $b);
    }
}
