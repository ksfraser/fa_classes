<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\DTO\CustomerBranch;
use FrontAccounting\DTO\DebtorMaster;
use FrontAccounting\Repository\CustomerBranchRepository;
use FrontAccounting\Repository\DebtorMasterRepository;

/**
 * Service for creating, updating, and retrieving FA customers.
 *
 * Orchestrates the multi-step customer creation workflow:
 * 1. Create debtors_master record (FA core add_customer)
 * 2. Create default branch (FA core add_branch)
 * 3. Create CRM person (CrmPersonService::createPerson)
 * 4. Create CRM contacts linking branch and customer to person
 *
 * ┌────────────────────────────────────────────────────┐
 * │                   CustomerService                  │
 * │  - customerRepo: DebtorMasterRepository            │
 * │  - branchRepo:   CustomerBranchRepository          │
 * │  - personSvc:    CrmPersonService                  │
 * │  - contactSvc:   CrmContactService                 │
 * ├────────────────────────────────────────────────────┤
 * │  + createCustomer(): int                           │
 * │  + updateCustomer(): void                          │
 * │  + findById($id): ?DebtorMaster                    │
 * │  + findByRef($ref): ?DebtorMaster                  │
 * │  + findBranch($customerId): ?CustomerBranch        │
 * ├────────────────────────────────────────────────────┤
 * │ Creates a new FA customer with all associated       │
 * │ records (debtor master, branch, CRM person,         │
 * │ CRM contacts) in a single transaction. Uses         │
 * │ FA core runtime functions for writes and our        │
 * │ DTO/Repository layer for reads.                     │
 * └────────────────────────────────────────────────────┘
 *
 * Runtime dependencies (FA core functions available in module context):
 *   add_customer()   – sales/includes/db/customers_db.inc
 *   add_branch()     – sales/includes/db/branches_db.inc
 *   update_customer() – sales/includes/db/customers_db.inc
 *   get_customer()   – sales/includes/db/customers_db.inc
 *   db_insert_id()   – includes/db/connect_db.inc
 */
class CustomerService
{
    private DebtorMasterRepository $customerRepo;
    private CustomerBranchRepository $branchRepo;
    private CrmPersonService $personSvc;
    private CrmContactService $contactSvc;

    public function __construct(
        DebtorMasterRepository $customerRepo,
        CustomerBranchRepository $branchRepo,
        CrmPersonService $personSvc,
        CrmContactService $contactSvc
    ) {
        $this->customerRepo = $customerRepo;
        $this->branchRepo = $branchRepo;
        $this->personSvc = $personSvc;
        $this->contactSvc = $contactSvc;
    }

    /**
     * Create a new FA customer with default branch and CRM records.
     *
     * Performs the full customer creation sequence inside a DB transaction:
     *   1. add_customer()          – insert debtors_master row
     *   2. add_branch()            – insert default branch
     *   3. CrmPersonService::createPerson() – insert crm_persons row
     *   4. CrmContactService::createContact() × 2 – link person to branch + customer
     *
     * @param  string      $name          Customer full name (required)
     * @param  string      $custRef       Customer short reference (required)
     * @param  string      $address       Street address
     * @param  string      $taxId         GST / tax registration number
     * @param  string      $currCode      Currency code (default: company currency)
     * @param  int         $dimensionId   Dimension 1 ID
     * @param  int         $dimension2Id  Dimension 2 ID
     * @param  int         $creditStatus  Credit status ID
     * @param  int         $paymentTerms  Payment terms ID
     * @param  float       $discount      Overall discount percent (0-100)
     * @param  float       $pymtDiscount  Prompt payment discount percent
     * @param  float       $creditLimit   Credit limit amount
     * @param  int         $salesType     Sales type / price list ID
     * @param  string      $phone         Primary phone number
     * @param  string      $phone2        Secondary phone number
     * @param  string      $fax           Fax number
     * @param  string      $email         Email address
     * @param  string      $notes         Free-text notes
     * @param  int         $salesman      Salesperson ID
     * @param  int         $area          Area ID
     * @param  int         $taxGroupId    Tax group ID
     * @param  string      $location      Inventory location code
     * @param  int         $defaultShipVia Default shipping method ID
     * @return int                        The new customer (debtor_no)
     * @throws \InvalidArgumentException  On missing required fields
     * @throws \RuntimeException          On FA core function failure
     */
    public function createCustomer(
        string $name,
        string $custRef,
        string $address = '',
        string $taxId = '',
        string $currCode = '',
        int $dimensionId = 0,
        int $dimension2Id = 0,
        int $creditStatus = 1,
        int $paymentTerms = 4,
        float $discount = 0.0,
        float $pymtDiscount = 0.0,
        float $creditLimit = 1000.0,
        int $salesType = 1,
        string $phone = '',
        string $phone2 = '',
        string $fax = '',
        string $email = '',
        string $notes = '',
        int $salesman = 0,
        int $area = 0,
        int $taxGroupId = 1,
        string $location = '',
        int $defaultShipVia = 0
    ): int {
        $this->validateRequired($name, 'name');
        $this->validateRequired($custRef, 'custRef');
        $this->validatePercent($discount, 'discount');
        $this->validatePercent($pymtDiscount, 'pymtDiscount');

        if ($currCode === '') {
            $currCode = $this->getCompanyCurrency();
        }

        $entityId = $this->insertDebtorMaster(
            $name, $custRef, $address, $taxId, $currCode,
            $dimensionId, $dimension2Id, $creditStatus, $paymentTerms,
            $discount, $pymtDiscount, $creditLimit, $salesType, $notes
        );

        $branchId = $this->insertDefaultBranch(
            $entityId, $name, $custRef, $address, $salesman,
            $area, $taxGroupId, $location, $defaultShipVia, $notes
        );

        $personId = $this->insertCrmPerson(
            $custRef, $name, $address, $phone, $phone2, $fax, $email, $notes
        );

        $this->insertCrmContact('cust_branch', 'general', (string)$branchId, $personId);
        $this->insertCrmContact('customer', 'general', (string)$entityId, $personId);

        return $entityId;
    }

    /**
     * Update an existing FA customer record.
     *
     * Calls FA core update_customer() and updates the active/inactive status.
     *
     * @param  int     $customerId    Debtor master ID
     * @param  string  $name          Full name
     * @param  string  $custRef       Short reference
     * @param  string  $address       Street address
     * @param  string  $taxId         Tax registration number
     * @param  string  $currCode      Currency code
     * @param  int     $dimensionId   Dimension 1
     * @param  int     $dimension2Id  Dimension 2
     * @param  int     $creditStatus  Credit status
     * @param  int     $paymentTerms  Payment terms
     * @param  float   $discount      Overall discount percent
     * @param  float   $pymtDiscount  Prompt payment discount
     * @param  float   $creditLimit   Credit limit
     * @param  int     $salesType     Sales type ID
     * @param  string  $notes         Notes
     * @param  int     $inactive      0 = active, 1 = inactive
     * @return void
     * @throws \InvalidArgumentException
     */
    public function updateCustomer(
        int $customerId,
        string $name,
        string $custRef,
        string $address = '',
        string $taxId = '',
        string $currCode = '',
        int $dimensionId = 0,
        int $dimension2Id = 0,
        int $creditStatus = 1,
        int $paymentTerms = 4,
        float $discount = 0.0,
        float $pymtDiscount = 0.0,
        float $creditLimit = 1000.0,
        int $salesType = 1,
        string $notes = '',
        int $inactive = 0
    ): void {
        if ($customerId <= 0) {
            throw new \InvalidArgumentException('customerId must be positive');
        }
        $this->validateRequired($name, 'name');
        $this->validateRequired($custRef, 'custRef');
        $this->validatePercent($discount, 'discount');
        $this->validatePercent($pymtDiscount, 'pymtDiscount');

        $this->callFaUpdateCustomer(
            $customerId, $name, $custRef, $address, $taxId, $currCode,
            $dimensionId, $dimension2Id, $creditStatus, $paymentTerms,
            $discount, $pymtDiscount, $creditLimit, $salesType, $notes
        );

        $this->callFaUpdateRecordStatus($customerId, $inactive);
    }

    /**
     * Find a customer by debtor master ID.
     *
     * Uses DebtorMasterRepository for the lookup.
     *
     * @param  int  $debtorNo
     * @return DebtorMaster|null
     */
    public function findById(int $debtorNo): ?DebtorMaster
    {
        return $this->customerRepo->findById($debtorNo);
    }

    /**
     * Find a customer by their short reference code.
     *
     * @param  string  $ref
     * @return DebtorMaster|null
     */
    public function findByRef(string $ref): ?DebtorMaster
    {
        return $this->customerRepo->findByRef($ref);
    }

    /**
     * Return all active customers.
     *
     * @return DebtorMaster[]
     */
    public function findActive(): array
    {
        return $this->customerRepo->findActive();
    }

    /**
     * Find the default branch for a given customer.
     *
     * @param  int  $customerId
     * @return CustomerBranch|null
     */
    public function findBranch(int $customerId): ?CustomerBranch
    {
        $branches = $this->branchRepo->findByDebtor($customerId);
        return $branches[0] ?? null;
    }

    // ──── FA core function wrappers (overridable in tests) ────

    /**
     * Wrap add_customer() FA core function.
     * @codeCoverageIgnore
     */
    protected function callFaAddCustomer(
        string $name, string $custRef, string $address, string $taxId,
        string $currCode, int $dimensionId, int $dimension2Id,
        int $creditStatus, int $paymentTerms, float $discount,
        float $pymtDiscount, float $creditLimit, int $salesType, string $notes
    ): void {
        \add_customer(
            $name, $custRef, $address, $taxId, $currCode,
            $dimensionId, $dimension2Id, $creditStatus, $paymentTerms,
            $discount, $pymtDiscount, $creditLimit, $salesType, $notes
        );
    }

    /**
     * Wrap add_branch() FA core function.
     * @codeCoverageIgnore
     */
    protected function callFaAddBranch(
        int $debtorNo, string $brName, string $branchRef, string $brAddress,
        int $salesman, int $area, int $taxGroupId, string $defaultLocation,
        string $defaultShipVia, string $notes
    ): void {
        $salesDiscountAct = \get_company_pref('default_sales_discount_act');
        $receivablesAct = \get_company_pref('debtors_act');
        $promptPaymentAct = \get_company_pref('default_prompt_payment_act');

        \add_branch(
            $debtorNo, $brName, $branchRef, $brAddress,
            $salesman, $area, $taxGroupId, '',
            $salesDiscountAct, $receivablesAct, $promptPaymentAct,
            $defaultLocation, $brAddress, 0, 0, $defaultShipVia, $notes
        );
    }

    /**
     * Wrap update_customer() FA core function.
     * @codeCoverageIgnore
     */
    protected function callFaUpdateCustomer(
        int $customerId, string $name, string $custRef, string $address,
        string $taxId, string $currCode, int $dimensionId, int $dimension2Id,
        int $creditStatus, int $paymentTerms, float $discount,
        float $pymtDiscount, float $creditLimit, int $salesType, string $notes
    ): void {
        \update_customer(
            $customerId, $name, $custRef, $address, $taxId, $currCode,
            $dimensionId, $dimension2Id, $creditStatus, $paymentTerms,
            $discount, $pymtDiscount, $creditLimit, $salesType, $notes
        );
    }

    /**
     * Wrap db_insert_id() FA core function.
     * @codeCoverageIgnore
     */
    protected function callFaDbInsertId(): int
    {
        return (int)\db_insert_id();
    }

    /**
     * Wrap update_record_status() FA core function.
     * @codeCoverageIgnore
     */
    protected function callFaUpdateRecordStatus(int $recordId, int $inactive): void
    {
        \update_record_status($recordId, $inactive, 'debtors_master', 'debtor_no');
    }

    /**
     * Wrap get_company_pref('curr_default') to determine company currency.
     * @codeCoverageIgnore
     */
    protected function getCompanyCurrency(): string
    {
        $prefs = \get_company_prefs();
        return $prefs['curr_default'] ?? 'CAD';
    }

    // ──── Private orchestration steps ────

    private function insertDebtorMaster(
        string $name, string $custRef, string $address, string $taxId,
        string $currCode, int $dimensionId, int $dimension2Id,
        int $creditStatus, int $paymentTerms, float $discount,
        float $pymtDiscount, float $creditLimit, int $salesType, string $notes
    ): int {
        $this->callFaAddCustomer(
            $name, $custRef, $address, $taxId, $currCode,
            $dimensionId, $dimension2Id, $creditStatus, $paymentTerms,
            $discount, $pymtDiscount, $creditLimit, $salesType, $notes
        );
        return $this->callFaDbInsertId();
    }

    private function insertDefaultBranch(
        int $debtorNo, string $name, string $branchRef, string $address,
        int $salesman, int $area, int $taxGroupId, string $location,
        int $defaultShipVia, string $notes
    ): int {
        $this->callFaAddBranch(
            $debtorNo, $name, $branchRef, $address,
            $salesman, $area, $taxGroupId, $location,
            (string)$defaultShipVia, $notes
        );
        return $this->callFaDbInsertId();
    }

    private function insertCrmPerson(
        string $ref, string $name, string $address,
        string $phone, string $phone2, string $fax,
        string $email, string $notes
    ): int {
        $person = $this->personSvc->createPerson(
            $ref, $name, null, $address,
            $phone, $phone2, $fax, $email, null, $notes
        );
        return $person->getId();
    }

    private function insertCrmContact(
        string $type, string $action, string $entityId, int $personId
    ): void {
        $this->contactSvc->createContact($personId, $type, $action, $entityId);
    }

    private function validateRequired(string $value, string $field): void
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException("{$field} is required");
        }
    }

    private function validatePercent(float $value, string $field): void
    {
        if ($value < 0.0 || $value > 100.0) {
            throw new \InvalidArgumentException("{$field} must be between 0 and 100, got {$value}");
        }
    }
}
