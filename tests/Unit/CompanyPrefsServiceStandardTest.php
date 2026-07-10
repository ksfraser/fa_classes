<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\SysPrefsRepository;
use FrontAccounting\Service\Standard\CompanyPrefsServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CompanyPrefsServiceStandardTest extends TestCase
{
    public function testGetCompanyPrefsReturnsAllPrefsAsMap(): void
    {
        $db = new FakeDbAdapter([
            ['name' => 'debtors_act', 'value' => '1100', 'category' => 0, 'type' => 0, 'length' => 0],
            ['name' => 'default_prompt_payment_act', 'value' => '4205', 'category' => 0, 'type' => 0, 'length' => 0],
        ]);
        $svc = new CompanyPrefsServiceStandard(new SysPrefsRepository($db));

        $result = $svc->getCompanyPrefs();

        $this->assertSame('1100', $result['debtors_act']);
        $this->assertSame('4205', $result['default_prompt_payment_act']);
    }

    public function testGetCompanyPrefReturnsValue(): void
    {
        $db = new FakeDbAdapter([[
            'name' => 'bank_charge_act',
            'value' => '4500',
            'category' => 0,
            'type' => 0,
            'length' => 0,
        ]]);
        $svc = new CompanyPrefsServiceStandard(new SysPrefsRepository($db));

        $this->assertSame('4500', $svc->getCompanyPref('bank_charge_act'));
    }

    public function testGetCompanyPrefReturnsEmptyWhenNotFound(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new CompanyPrefsServiceStandard(new SysPrefsRepository($db));

        $this->assertSame('', $svc->getCompanyPref('nonexistent'));
    }
}
