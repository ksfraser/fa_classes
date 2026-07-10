<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\DebtorMasterRepository;
use FrontAccounting\Service\Standard\CustomerServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerServiceStandardTest extends TestCase
{
    public function testGetCustomerCurrencyReturnsCurrencyCode(): void
    {
        $db = new FakeDbAdapter([[
            'debtor_no' => 1,
            'name' => 'Test Customer',
            'debtor_ref' => 'C001',
            'address' => '123 Main St',
            'tax_id' => '',
            'curr_code' => 'USD',
            'sales_type' => 1,
            'dimension_id' => 0,
            'dimension2_id' => 0,
            'credit_status' => 0,
            'payment_terms' => 30,
            'discount' => 0.0,
            'pymt_discount' => 0.0,
            'credit_limit' => 1000.0,
            'notes' => '',
            'inactive' => 0,
        ]]);
        $svc = new CustomerServiceStandard(new DebtorMasterRepository($db));

        $this->assertSame('USD', $svc->getCustomerCurrency(1));
    }

    public function testGetCustomerCurrencyReturnsEmptyWhenNotFound(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new CustomerServiceStandard(new DebtorMasterRepository($db));

        $this->assertSame('', $svc->getCustomerCurrency(999));
    }

    public function testGetCustomerHabitReturnsDiscounts(): void
    {
        $db = new FakeDbAdapter([[
            'debtor_no' => 1,
            'name' => 'Test Customer',
            'debtor_ref' => 'C001',
            'address' => '',
            'tax_id' => '',
            'curr_code' => 'USD',
            'sales_type' => 1,
            'dimension_id' => 0,
            'dimension2_id' => 0,
            'credit_status' => 0,
            'payment_terms' => 30,
            'discount' => 2.5,
            'pymt_discount' => 1.0,
            'credit_limit' => 1000.0,
            'notes' => '',
            'inactive' => 0,
        ]]);
        $svc = new CustomerServiceStandard(new DebtorMasterRepository($db));

        $result = $svc->getCustomerHabit(1);

        $this->assertSame(2.5, $result['discount']);
        $this->assertSame(1.0, $result['pymt_discount']);
    }
}
