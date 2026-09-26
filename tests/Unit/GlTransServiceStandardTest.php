<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Service\Standard\GlTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class GlTransServiceStandardTest extends TestCase
{
    public function testAddGlTransInsertsRowAndReturnsAmount(): void
    {
        $db = new FakeDbAdapter([['next_counter' => 5]], 0, 1);
        $repo = new GlTransRepository($db);
        $svc = new GlTransServiceStandard($repo);

        $result = $svc->addGlTrans(
            12,
            201,
            '2026-07-10',
            '1100',
            0,
            0,
            '',
            100.00,
            null,
            '',
            0
        );

        $this->assertSame(100.00, $result);
        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
        $this->assertStringContainsStringIgnoringCase('gl_trans', $db->lastSql);
    }

    public function testAddGlTransCreatesSequentialCounter(): void
    {
        $db = new FakeDbAdapter([['next_counter' => 42]], 0, 1);
        $repo = new GlTransRepository($db);
        $svc = new GlTransServiceStandard($repo);

        $svc->addGlTrans(12, 201, '2026-07-10', '1100', 0, 0, '', 50.00);

        $this->assertContains(42, $db->lastParams, 'Counter 42 should be in INSERT params');
    }

    public function testAddGlTransStoresPersonTypeAndIdWhenGiven(): void
    {
        $db = new FakeDbAdapter([['next_counter' => 1]], 0, 1);
        $repo = new GlTransRepository($db);
        $svc = new GlTransServiceStandard($repo);

        $svc->addGlTrans(
            12,
            201,
            '2026-07-10',
            '1100',
            0,
            0,
            'Bank charge',
            -5.00,
            null,
            PT_CUSTOMER,
            42
        );

        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
    }

    public function testAddGlTransCustomerDelegatesWithCustomerType(): void
    {
        $db = new FakeDbAdapter([['next_counter' => 7]], 0, 1);
        $repo = new GlTransRepository($db);
        $svc = new GlTransServiceStandard($repo);

        $result = $svc->addGlTransCustomer(
            12,
            201,
            '2026-07-10',
            '1200',
            0,
            0,
            -500.00,
            99,
            'Test'
        );

        $this->assertSame(-500.00, $result);
        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
    }

    public function testGetNextCounterReturnsOneWhenNoExistingRows(): void
    {
        $db = new FakeDbAdapter([['next_counter' => 1]], 0, 1);
        $repo = new GlTransRepository($db);

        $counter = $repo->getNextCounter(12, 999);

        $this->assertSame(1, $counter);
        $this->assertStringContainsString('MAX(counter)', $db->lastSql);
    }
}
