<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Service\Native\ExchangeRateServiceNative;
use FrontAccounting\Service\Standard\ExchangeRateServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class ExchangeRateServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testDirectRateFromEurMatches(): void
    {
        // famock: EUR_USD = 1.18
        $native = new ExchangeRateServiceNative();

        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'EUR',
            'rate_buy' => 1.18,
            'rate_sell' => 1.25,
            'date_' => '2026-07-10',
        ]]);
        $standard = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $this->assertSame(
            $native->getExchangeRateFromTo('EUR', 'USD', '2026-07-10'),
            $standard->getExchangeRateFromTo('EUR', 'USD', '2026-07-10')
        );
    }

    public function testInverseRateForCadMatches(): void
    {
        // famock: USD_CAD = 1.30  => CAD rate_buy = 1/1.30 ~ 0.76923
        $native = new ExchangeRateServiceNative();
        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'CAD',
            'rate_buy' => 1.0 / 1.30,
            'rate_sell' => 1.0 / 1.30,
            'date_' => '2026-07-10',
        ]]);
        $standard = new ExchangeRateServiceStandard(new ExchangeRateRepository($db));

        $this->assertEqualsWithDelta(
            $native->getExchangeRateFromTo('USD', 'CAD', '2026-07-10'),
            $standard->getExchangeRateFromTo('USD', 'CAD', '2026-07-10'),
            0.001
        );
    }

    public function testSameCurrencyBothReturnOne(): void
    {
        $native = new ExchangeRateServiceNative();
        $standard = new ExchangeRateServiceStandard(new ExchangeRateRepository(new FakeDbAdapter([])));

        $this->assertSame(
            $native->getExchangeRateFromTo('USD', 'USD', '2026-07-10'),
            $standard->getExchangeRateFromTo('USD', 'USD', '2026-07-10')
        );
    }
}
