<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\SysPrefsRepository;
use FrontAccounting\Service\Native\CompanyPrefsServiceNative;
use FrontAccounting\Service\Standard\CompanyPrefsServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CompanyPrefsServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testGetCompanyPrefBothReturnSame(): void
    {
        // famock: get_company_pref('debtors_act') returns '1100'
        $native = new CompanyPrefsServiceNative();

        // SysPrefsRepository::findByName() hydrates a SysPref DTO via findOne()
        // findOne() uses hydrate() which expects these columns
        $db = new FakeDbAdapter([[
            'name' => 'debtors_act',
            'value' => '1100',
            'description' => 'Debtors Account',
            'category' => 0,
            'type' => 0,
            'length' => 0,
            'user_id' => null,
            'company_id' => null,
        ]]);
        $standard = new CompanyPrefsServiceStandard(new SysPrefsRepository($db));

        $this->assertSame(
            $native->getCompanyPref('debtors_act'),
            $standard->getCompanyPref('debtors_act')
        );
    }

    public function testGetCompanyPrefBothReturnEmptyWhenNotFound(): void
    {
        $native = new CompanyPrefsServiceNative();
        $standard = new CompanyPrefsServiceStandard(new SysPrefsRepository(new FakeDbAdapter([])));

        $this->assertSame(
            $native->getCompanyPref('nonexistent'),
            $standard->getCompanyPref('nonexistent')
        );
    }

    public function testGetCompanyPrefsBothContainSameKeys(): void
    {
        $native = new CompanyPrefsServiceNative();

        $rows = [
            ['name' => 'debtors_act', 'value' => '1100', 'category' => 0, 'type' => 0, 'length' => 0,
             'user_id' => null, 'company_id' => null, 'description' => 'a'],
            ['name' => 'bank_charge_act', 'value' => '4500', 'category' => 0, 'type' => 0, 'length' => 0,
             'user_id' => null, 'company_id' => null, 'description' => 'b'],
            ['name' => 'default_prompt_payment_act', 'value' => '4205', 'category' => 0, 'type' => 0, 'length' => 0,
             'user_id' => null, 'company_id' => null, 'description' => 'c'],
        ];
        $db = new FakeDbAdapter($rows);
        $standard = new CompanyPrefsServiceStandard(new SysPrefsRepository($db));

        $nativeResult = $native->getCompanyPrefs();
        $standardResult = $standard->getCompanyPrefs();

        // Common keys should have same values
        foreach (['debtors_act', 'bank_charge_act', 'default_prompt_payment_act'] as $key) {
            $this->assertSame(
                $nativeResult[$key] ?? null,
                $standardResult[$key] ?? null
            );
        }
    }
}
