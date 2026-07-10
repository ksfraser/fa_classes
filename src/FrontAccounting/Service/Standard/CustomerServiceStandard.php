<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\DebtorMasterRepository;
use FrontAccounting\Service\Contracts\CustomerService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of CustomerService.
 *
 * Queries debtors_master directly instead of FA core's
 * get_customer_currency() / get_customer_habit().
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │                CustomerServiceStandard                    │
 * │  - debtorMasterRepo: DebtorMasterRepository               │
 * ├──────────────────────────────────────────────────────────┤
 * │  + getCustomerCurrency($customerId): string              │
 * │  + getCustomerHabit($customerId): array                  │
 * └──────────────────────────────────────────────────────────┘
 */
final class CustomerServiceStandard implements CustomerService
{
    private DebtorMasterRepository $debtorMasterRepo;

    public function __construct(DebtorMasterRepository $debtorMasterRepo)
    {
        $this->debtorMasterRepo = $debtorMasterRepo;
    }

    public function getCustomerCurrency(int $customerId): string
    {
        $customer = $this->debtorMasterRepo->findById($customerId);
        return $customer !== null ? $customer->getCurrCode() : '';
    }

    /**
     * @return array<string, mixed>
     */
    public function getCustomerHabit(int $customerId): array
    {
        $customer = $this->debtorMasterRepo->findById($customerId);
        if ($customer === null) {
            return [];
        }
        return [
            'discount' => $customer->getDiscount(),
            'pymt_discount' => $customer->getPymtDiscount(),
        ];
    }
}
