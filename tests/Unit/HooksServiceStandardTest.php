<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Service\Contracts\HooksService;
use FrontAccounting\Service\Standard\HooksServiceStandard;
use PHPUnit\Framework\TestCase;

final class HooksServiceStandardTest extends TestCase
{
    public function testImplementsHooksService(): void
    {
        $svc = new HooksServiceStandard();
        $this->assertInstanceOf(HooksService::class, $svc);
    }

    public function testPreWriteDoesNotThrow(): void
    {
        $svc = new HooksServiceStandard();
        $svc->preWrite(new \stdClass(), 12);
        $this->expectNotToPerformAssertions();
    }

    public function testPostWriteDoesNotThrow(): void
    {
        $svc = new HooksServiceStandard();
        $svc->postWrite(new \stdClass(), 12);
        $this->expectNotToPerformAssertions();
    }
}
