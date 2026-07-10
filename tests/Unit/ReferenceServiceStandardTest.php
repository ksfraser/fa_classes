<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\Standard\ReferenceServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class ReferenceServiceStandardTest extends TestCase
{
    public function testSaveReferenceInserts(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new ReferenceServiceStandard(new RefsRepository($db));

        $svc->saveReference(12, 201, 'PAY-001');

        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
    }

    public function testCheckReferenceReturnsTrueWhenUnique(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new ReferenceServiceStandard(new RefsRepository($db));

        $this->assertTrue($svc->checkReference('PAY-001', 12));
    }

    public function testCheckReferenceReturnsFalseWhenDuplicate(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'type' => 12,
            'trans_no' => 200,
            'reference' => 'PAY-001',
        ]]);
        $svc = new ReferenceServiceStandard(new RefsRepository($db));

        $this->assertFalse($svc->checkReference('PAY-001', 12, 201));
    }
}
