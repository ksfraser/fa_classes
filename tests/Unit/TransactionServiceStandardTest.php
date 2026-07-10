<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Service\Contracts\TransactionService;
use FrontAccounting\Service\Standard\TransactionServiceStandard;
use PHPUnit\Framework\TestCase;

final class TransactionServiceStandardTest extends TestCase
{
    public function testImplementsTransactionService(): void
    {
        $svc = new TransactionServiceStandard();
        $this->assertInstanceOf(TransactionService::class, $svc);
    }

    public function testBeginDoesNotThrow(): void
    {
        $svc = new TransactionServiceStandard();
        $svc->begin();
        $this->expectNotToPerformAssertions();
    }

    public function testCommitDoesNotThrow(): void
    {
        $svc = new TransactionServiceStandard();
        $svc->commit();
        $this->expectNotToPerformAssertions();
    }
}
