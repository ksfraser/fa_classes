<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Service\Standard\ExchangeRateServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class ExchangeRateServiceStandardTest extends TestCase
{
    public function testSameCurrencyReturnsOne(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $this->assertSame(1.0, $svc->getExchangeRateFromTo('USD', 'USD', '2026-07-10'));
    }

    public function testDirectRateForFromCurrency(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'EUR',
            'rate_buy' => 1.2,
            'rate_sell' => 1.25,
            'date_' => '2026-07-10',
        ]]);
        $svc = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $result = $svc->getExchangeRateFromTo('EUR', 'USD', '2026-07-10');

        $this->assertSame(1.2, $result);
    }

    public function testInverseRateForToCurrency(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'CAD',
            'rate_buy' => 0.75,
            'rate_sell' => 0.78,
            'date_' => '2026-07-10',
        ]]);
        $svc = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $result = $svc->getExchangeRateFromTo('USD', 'CAD', '2026-07-10');

        $this->assertEqualsWithDelta(1.3333, $result, 0.001);
    }

    public function testFallsBackToOneWhenNoRateFound(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $this->assertSame(1.0, $svc->getExchangeRateFromTo('XYZ', 'ABC', '2026-07-10'));
    }
}
