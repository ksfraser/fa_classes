<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\Native\ReferenceServiceNative;
use FrontAccounting\Service\Standard\ReferenceServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class ReferenceServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testCheckReferenceBothReturnTrueWhenUnique(): void
    {
        // famock: check_reference(...) returns true
        $native = new ReferenceServiceNative();

        // Standard: no existing refs → returns true
        $db = new FakeDbAdapter([]);
        $standard = new ReferenceServiceStandard(new RefsRepository($db));

        $nativeResult = $native->checkReference('PAY-001', 12);
        $standardResult = $standard->checkReference('PAY-001', 12);

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testCheckReferenceWhenDuplicateDiffers(): void
    {
        // famock always returns true even for duplicates
        $native = new ReferenceServiceNative();

        // Standard detects duplicate (same ref, different trans_no)
        $db = new FakeDbAdapter([[
            'id' => 1,
            'type' => 12,
            'trans_no' => 200,
            'reference' => 'PAY-001',
        ]]);
        $standard = new ReferenceServiceStandard(new RefsRepository($db));

        $nativeResult = $native->checkReference('PAY-001', 12, 201);
        $standardResult = $standard->checkReference('PAY-001', 12, 201);

        // Native stub always true, Standard does real duplicate detection
        $this->assertTrue($nativeResult);
        $this->assertFalse($standardResult);
    }
}
