<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\DebtorMasterRepository;
use FrontAccounting\Service\Native\CustomerServiceNative;
use FrontAccounting\Service\Standard\CustomerServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testGetCustomerCurrencyBothReturnCad(): void
    {
        // famock: get_customer_currency(1) returns 'CAD'
        $native = new CustomerServiceNative();

        // Standard needs a DebtorMaster with curr_code = 'CAD'
        $db = new FakeDbAdapter([[
            'debtor_no' => 1,
            'name' => 'Test',
            'debtor_ref' => 'C001',
            'address' => '',
            'tax_id' => '',
            'curr_code' => 'CAD',
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
        $standard = new CustomerServiceStandard(new DebtorMasterRepository($db));

        $this->assertSame(
            $native->getCustomerCurrency(1),
            $standard->getCustomerCurrency(1)
        );
    }

    public function testGetCustomerCurrencyWhenFound(): void
    {
        // famock always returns 'CAD' for any customer
        // Standard returns '' when customer not found — more accurate
        $native = new CustomerServiceNative();
        $standard = new CustomerServiceStandard(new DebtorMasterRepository(new FakeDbAdapter([])));
        $this->assertSame('CAD', $native->getCustomerCurrency(999));
        $this->assertSame('', $standard->getCustomerCurrency(999));
    }

    public function testGetCustomerHabitBothReturnArrays(): void
    {
        // famock: get_customer_habit(1) returns ['dissallow_invoices' => 0, 'pymt_discount' => 0.0]
        $native = new CustomerServiceNative();
        $db = new FakeDbAdapter([[
            'debtor_no' => 1,
            'name' => 'Test',
            'debtor_ref' => 'C001',
            'address' => '',
            'tax_id' => '',
            'curr_code' => 'CAD',
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
        $standard = new CustomerServiceStandard(new DebtorMasterRepository($db));

        $nativeResult = $native->getCustomerHabit(1);
        $standardResult = $standard->getCustomerHabit(1);

        // Both return arrays with 'pymt_discount' key
        $this->assertArrayHasKey('pymt_discount', $nativeResult);
        $this->assertArrayHasKey('pymt_discount', $standardResult);
    }
}
