<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Service\Native\GlTransServiceNative;
use FrontAccounting\Service\Standard\GlTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class GlTransServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testAddGlTransBothReturnAmount(): void
    {
        // famock: add_gl_trans(...) returns $amount
        $native = new GlTransServiceNative();

        // Standard: getNextCounter returns MAX(counter)+1, insert, return amount
        // FakeDbAdapter returns first row for MAX query, then insert happens
        $db = new FakeDbAdapter([['next_counter' => 1]], 0, 1);
        $standard = new GlTransServiceStandard(new GlTransRepository($db));

        $nativeResult = $native->addGlTrans(
            12, 201, '2026-07-10', '1100', 0, 0, '', 100.00
        );
        $standardResult = $standard->addGlTrans(
            12, 201, '2026-07-10', '1100', 0, 0, '', 100.00
        );

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testAddGlTransCustomerBothReturnAmount(): void
    {
        $native = new GlTransServiceNative();
        $db = new FakeDbAdapter([['next_counter' => 1]], 0, 1);
        $standard = new GlTransServiceStandard(new GlTransRepository($db));

        $nativeResult = $native->addGlTransCustomer(
            12, 201, '2026-07-10', '1200', 0, 0, -500.00, 99
        );
        $standardResult = $standard->addGlTransCustomer(
            12, 201, '2026-07-10', '1200', 0, 0, -500.00, 99
        );

        $this->assertSame($nativeResult, $standardResult);
    }
}
