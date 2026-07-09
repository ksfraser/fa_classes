<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\CrmContactRepository;
use FrontAccounting\Repository\CrmPersonRepository;
use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Repository\DebtorMasterRepository;
use FrontAccounting\Service\CrmContactService;
use FrontAccounting\Service\CrmPersonService;
use FrontAccounting\Service\CustomerService;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CustomerServiceTest extends TestCase
{
    private CustomerService $svc;

    protected function setUp(): void
    {
        $db = new FakeDbAdapter([], 42);
        $customerRepo = new DebtorMasterRepository($db);
        $branchRepo = new CustomerBranchRepository($db);
        $personRepo = new CrmPersonRepository($db);
        $contactRepo = new CrmContactRepository($db);

        $personSvc = new CrmPersonService($personRepo);
        $contactSvc = new CrmContactService($contactRepo);

        $this->svc = $this->getMockBuilder(CustomerService::class)
            ->setConstructorArgs([
                $customerRepo,
                $branchRepo,
                $personSvc,
                $contactSvc,
            ])
            ->onlyMethods([
                'callFaAddCustomer',
                'callFaAddBranch',
                'callFaDbInsertId',
                'callFaUpdateCustomer',
                'callFaUpdateRecordStatus',
                'getCompanyCurrency',
            ])
            ->getMock();
    }

    public function testCreateCustomerSuccess(): void
    {
        $this->svc->expects($this->once())->method('callFaAddCustomer');
        $this->svc->expects($this->exactly(2))->method('callFaDbInsertId')
            ->willReturnOnConsecutiveCalls(42, 99);

        $result = $this->svc->createCustomer(
            'Test Customer',
            'CUST001',
            '123 Main St',
            'GST123',
            'CAD',
            1, 2, 1, 4, 0.0, 0.0, 1000.0, 1,
            '555-0100', '', '', 'test@example.com', '',
            2, 1, 1, 'LOC', 0
        );

        $this->assertSame(42, $result);
    }

    public function testCreateCustomerUsesCompanyCurrencyWhenEmpty(): void
    {
        $this->svc->expects($this->once())->method('callFaAddCustomer');
        $this->svc->expects($this->exactly(2))->method('callFaDbInsertId')
            ->willReturnOnConsecutiveCalls(43, 100);
        $this->svc->expects($this->once())->method('getCompanyCurrency')
            ->willReturn('USD');

        $result = $this->svc->createCustomer(
            'Test Customer',
            'CUST001', '', '', '',
            0, 0, 1, 4, 0.0, 0.0, 1000.0, 1,
            '', '', '', '', '',
            0, 0, 1, '', 0
        );

        $this->assertSame(43, $result);
    }

    public function testCreateCustomerThrowsOnEmptyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');
        $this->svc->createCustomer('', 'CUST001');
    }

    public function testCreateCustomerThrowsOnEmptyRef(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('custRef is required');
        $this->svc->createCustomer('Test Customer', '');
    }

    public function testCreateCustomerThrowsOnInvalidDiscount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('discount must be between 0 and 100');
        $this->svc->createCustomer('Test Customer', 'CUST001', '', '', '', 0, 0, 1, 4, 150.0);
    }

    public function testUpdateCustomerSuccess(): void
    {
        $this->svc->expects($this->once())->method('callFaUpdateCustomer');
        $this->svc->expects($this->once())->method('callFaUpdateRecordStatus');

        $this->svc->updateCustomer(42, 'Updated Name', 'CUST001', '', '', '', 0, 0, 1, 4, 0.0, 0.0, 1000.0, 1, '', 0);

        $this->assertTrue(true);
    }

    public function testUpdateCustomerThrowsOnInvalidId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('customerId must be positive');
        $this->svc->updateCustomer(0, 'Name', 'REF');
    }

    public function testFindByIdDelegates(): void
    {
        $this->svc = new CustomerService(
            new DebtorMasterRepository(new FakeDbAdapter([['debtor_no' => '3', 'name' => 'Acme', 'debtor_ref' => 'ACME001', 'address' => null, 'tax_id' => '', 'curr_code' => 'USD', 'sales_type' => '1', 'dimension_id' => '0', 'dimension2_id' => '0', 'credit_status' => '0', 'payment_terms' => null, 'discount' => '0', 'pymt_discount' => '0', 'credit_limit' => '1000', 'notes' => '', 'inactive' => '0']], 1)),
            new CustomerBranchRepository(new FakeDbAdapter()),
            new CrmPersonService(new CrmPersonRepository(new FakeDbAdapter())),
            new CrmContactService(new CrmContactRepository(new FakeDbAdapter()))
        );

        $result = $this->svc->findById(3);

        $this->assertNotNull($result);
        $this->assertSame(3, $result->getDebtorNo());
        $this->assertSame('Acme', $result->getName());
    }

    public function testFindByRefDelegates(): void
    {
        $db = new FakeDbAdapter([], 0);
        $svc = new CustomerService(
            new DebtorMasterRepository($db),
            new CustomerBranchRepository(new FakeDbAdapter()),
            new CrmPersonService(new CrmPersonRepository(new FakeDbAdapter())),
            new CrmContactService(new CrmContactRepository(new FakeDbAdapter()))
        );

        $result = $svc->findByRef('ACME001');

        $this->assertNull($result);
    }

    public function testFindActiveDelegates(): void
    {
        $db = new FakeDbAdapter([], 0);
        $svc = new CustomerService(
            new DebtorMasterRepository($db),
            new CustomerBranchRepository(new FakeDbAdapter()),
            new CrmPersonService(new CrmPersonRepository(new FakeDbAdapter())),
            new CrmContactService(new CrmContactRepository(new FakeDbAdapter()))
        );

        $results = $svc->findActive();

        $this->assertIsArray($results);
    }

    public function testFindBranchDelegates(): void
    {
        $db = new FakeDbAdapter([['branch_code' => '1', 'debtor_no' => '42', 'br_name' => 'Default', 'branch_ref' => 'REF', 'br_address' => '', 'area' => null, 'salesman' => '0', 'contact_name' => '', 'tax_group_id' => '0', 'default_location' => '', 'sales_account' => '', 'sales_discount_account' => '', 'receivables_account' => '', 'payment_discount_account' => '', 'default_ship_via' => '0', 'disable_trans' => '0', 'br_post_address' => '', 'group_no' => '0', 'notes' => '', 'inactive' => '0']], 1);
        $svc = new CustomerService(
            new DebtorMasterRepository(new FakeDbAdapter()),
            new CustomerBranchRepository($db),
            new CrmPersonService(new CrmPersonRepository(new FakeDbAdapter())),
            new CrmContactService(new CrmContactRepository(new FakeDbAdapter()))
        );

        $result = $svc->findBranch(42);

        $this->assertNotNull($result);
        $this->assertSame(42, $result->getDebtorNo());
    }
}
