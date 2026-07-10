<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\ChartMasterRepository;
use FrontAccounting\Repository\ExchangeRateRepository;
use FrontAccounting\Repository\FiscalYearRepository;
use FrontAccounting\Service\Native\MiscServiceNative;
use FrontAccounting\Service\Standard\MiscServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class MiscServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testCheckNumBothReturnTrue(): void
    {
        // famock: check_num(...) returns true, Standard always returns true
        $native = new MiscServiceNative();
        $standard = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );

        $this->assertSame(
            $native->checkNum('amount'),
            $standard->checkNum('amount')
        );
    }

    public function testHasCurrencyRatesBothReturnTrue(): void
    {
        // famock: db_has_currency_rates(...) returns true
        $native = new MiscServiceNative();
        $db = new FakeDbAdapter([[
            'id' => 1,
            'curr_code' => 'USD',
            'rate_buy' => 1.0,
            'rate_sell' => 1.0,
            'date_' => '2026-07-10',
        ]]);
        $standard = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository($db),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );

        $this->assertSame(
            $native->hasCurrencyRates('USD', '2026-07-10'),
            $standard->hasCurrencyRates('USD', '2026-07-10')
        );
    }

    public function testIsDateInFiscalYearBothReturnTrue(): void
    {
        // famock: is_date_in_fiscalyear(...) returns true
        $native = new MiscServiceNative();
        $db = new FakeDbAdapter([[
            'id' => 1,
            'begin' => '2026-01-01',
            'end' => '2026-12-31',
            'closed' => 0,
        ]]);
        $standard = new MiscServiceStandard(
            new FiscalYearRepository($db),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );

        $this->assertSame(
            $native->isDateInFiscalYear('2026-06-15'),
            $standard->isDateInFiscalYear('2026-06-15')
        );
    }

    public function testNewDocDateBothReturnGivenDate(): void
    {
        $native = new MiscServiceNative();
        $standard = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository(new FakeDbAdapter([]))
        );

        $this->assertSame(
            $native->newDocDate('2026-07-10'),
            $standard->newDocDate('2026-07-10')
        );
    }

    public function testGetGlAccountBothReturnArrayForExisting(): void
    {
        // famock: get_gl_account('1100') returns array with account_code and account_name
        $native = new MiscServiceNative();

        $db = new FakeDbAdapter([[
            'account_code' => '1100',
            'account_type' => 1,
            'account_name' => 'Test Account',
        ]]);
        $standard = new MiscServiceStandard(
            new FiscalYearRepository(new FakeDbAdapter([])),
            new ExchangeRateRepository(new FakeDbAdapter([])),
            new ChartMasterRepository($db)
        );

        $nativeResult = $native->getGlAccount('1100');
        $standardResult = $standard->getGlAccount('1100');

        $this->assertIsArray($nativeResult);
        $this->assertIsArray($standardResult);
        $this->assertSame($nativeResult['account_code'], $standardResult['account_code']);
    }
}
