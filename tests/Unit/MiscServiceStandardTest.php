<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\ChartMasterRepository;
use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Repository\FiscalYearRepository;
use FrontAccounting\Service\Standard\MiscServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class MiscServiceStandardTest extends TestCase
{
    public function testCheckNumAlwaysReturnsTrue(): void
    {
        $svc = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );
        $this->assertTrue($svc->checkNum('amount'));
    }

    public function testHasCurrencyRatesReturnsTrueWhenRateExists(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'USD',
            'rate_buy' => 1.0,
            'rate_sell' => 1.0,
            'date_' => '2026-07-10',
        ]]);
        $svc = new MiscServiceStandard(
            new FiscalYearRepository($db),
            new ExchangeRateRepository($db),
            new ChartMasterRepository($db)
        );
        $this->assertTrue($svc->hasCurrencyRates('USD', '2026-07-10'));
    }

    public function testHasCurrencyRatesReturnsFalseWhenNoRate(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new MiscServiceStandard(
            new FiscalYearRepository($db),
            new ExchangeRateRepository($db),
            new ChartMasterRepository($db)
        );
        $this->assertFalse($svc->hasCurrencyRates('USD', '2026-07-10'));
    }

    public function testIsDateInFiscalYearReturnsTrueWhenFound(): void
    {
        $db = new FakeDbAdapter([[
            'id' => 1,
            'begin' => '2026-01-01',
            'end' => '2026-12-31',
            'closed' => 0,
        ]]);
        $svc = new MiscServiceStandard(
            new FiscalYearRepository($db),
            new ExchangeRateRepository($db),
            new ChartMasterRepository($db)
        );
        $this->assertTrue($svc->isDateInFiscalYear('2026-06-15'));
    }

    public function testNewDocDateReturnsGivenDate(): void
    {
        $svc = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );
        $this->assertSame('2026-07-10', $svc->newDocDate('2026-07-10'));
    }

    public function testNewDocDateDefaultsToToday(): void
    {
        $svc = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );
        $this->assertStringMatchesFormat('%d-%d-%d', $svc->newDocDate());
    }

    public function testGetGlAccountReturnsRowWhenFound(): void
    {
        $db = new FakeDbAdapter([[
            'account_code' => '1100',
            'account_type' => 1,
            'account_name' => 'Cash',
        ]]);
        $svc = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository($db)
        );
        $result = $svc->getGlAccount('1100');
        $this->assertIsArray($result);
        $this->assertSame('1100', $result['account_code']);
    }

    public function testGetGlAccountReturnsFalseWhenNotFound(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new MiscServiceStandard(
            new FiscalYearRepository($db),
            new ExchangeRateRepository($db),
            new ChartMasterRepository($db)
        );
        $this->assertFalse($svc->getGlAccount('9999'));
    }
}
