<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\Service\Contracts\BankAccountService;
use FrontAccounting\Service\Contracts\BankTransferService;
use FrontAccounting\Service\Contracts\BankTransService;
use FrontAccounting\Service\Contracts\CommentsService;
use FrontAccounting\Service\Contracts\CompanyPrefsService;
use FrontAccounting\Service\Contracts\CustomerService;
use FrontAccounting\Service\Contracts\DebtorTransService;
use FrontAccounting\Service\Contracts\ExchangeRateService;
use FrontAccounting\Service\Contracts\GlTransService;
use FrontAccounting\Service\Contracts\HooksService;
use FrontAccounting\Service\Contracts\MiscService;
use FrontAccounting\Service\Contracts\OrderToDeliveryService;
use FrontAccounting\Service\Contracts\ReferenceService;
use FrontAccounting\Service\Contracts\TransactionService;
use FrontAccounting\Service\Native\BankAccountServiceNative;
use FrontAccounting\Service\Native\BankTransferServiceNative;
use FrontAccounting\Service\Native\BankTransServiceNative;
use FrontAccounting\Service\Native\CommentsServiceNative;
use FrontAccounting\Service\Native\CompanyPrefsServiceNative;
use FrontAccounting\Service\Native\CustomerServiceNative;
use FrontAccounting\Service\Native\DebtorTransServiceNative;
use FrontAccounting\Service\Native\ExchangeRateServiceNative;
use FrontAccounting\Service\Native\GlTransServiceNative;
use FrontAccounting\Service\Native\HooksServiceNative;
use FrontAccounting\Service\Native\MiscServiceNative;
use FrontAccounting\Service\Native\OrderToDeliveryServiceNative;
use FrontAccounting\Service\Native\ReferenceServiceNative;
use FrontAccounting\Service\Native\TransactionServiceNative;

/**
 * @since 2026-07-09
 * Runtime registry of service implementations.
 *
 * Defaults every slot to a *ServiceNative (Fa core wrapper).  Callers
 * override individual slots via set*() with DTO/Repository-based
 * implementations.  No mode switching — the composition root is the
 * only place that decides.
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │                    ServiceRuntimeConfig                   │
 * │                                                          │
 * │  Registry (set / get)      Default (lazy)                │
 * │  ─────────────────────     ──────────────────────────    │
 *  │  setGlTrans(...)           GlTransServiceNative          │
 *  │  setBankTrans(...)         BankTransServiceNative        │
 *  │  setBankTransfer(...)      BankTransferServiceNative     │
 *  │  setDebtorTrans(...)       DebtorTransServiceNative      │
 *  │  setComments(...)          CommentsServiceNative         │
 *  │  setReference(...)         ReferenceServiceNative        │
 *  │  setBankAccount(...)       BankAccountServiceNative      │
 *  │  setCompanyPrefs(...)      CompanyPrefsServiceNative     │
 *  │  setCustomer(...)          CustomerServiceNative         │
 *  │  setExchangeRate(...)      ExchangeRateServiceNative     │
 *  │  setHooks(...)             HooksServiceNative            │
 *  │  setTransaction(...)       TransactionServiceNative      │
 *  │  setMisc(...)              MiscServiceNative             │
 * └──────────────────────────────────────────────────────────┘
 */
class ServiceRuntimeConfig
{
    /** @var GlTransService|null */
    private $glTrans = null;
    /** @var BankTransService|null */
    private $bankTrans = null;
    /** @var BankTransferService|null */
    private $bankTransfer = null;
    /** @var DebtorTransService|null */
    private $debtorTrans = null;
    /** @var CommentsService|null */
    private $comments = null;
    /** @var ReferenceService|null */
    private $reference = null;
    /** @var BankAccountService|null */
    private $bankAccount = null;
    /** @var CompanyPrefsService|null */
    private $companyPrefs = null;
    /** @var CustomerService|null */
    private $customer = null;
    /** @var ExchangeRateService|null */
    private $exchangeRate = null;
    /** @var HooksService|null */
    private $hooks = null;
    /** @var TransactionService|null */
    private $transaction = null;
    /** @var MiscService|null */
    private $misc = null;
    /** @var OrderToDeliveryService|null */
    private $orderToDelivery = null;

    // ── Setters ──────────────────────────────────────────────

    public function setGlTrans(GlTransService $impl): void { $this->glTrans = $impl; }
    public function setBankTrans(BankTransService $impl): void { $this->bankTrans = $impl; }
    public function setBankTransfer(BankTransferService $impl): void { $this->bankTransfer = $impl; }
    public function setDebtorTrans(DebtorTransService $impl): void { $this->debtorTrans = $impl; }
    public function setComments(CommentsService $impl): void { $this->comments = $impl; }
    public function setReference(ReferenceService $impl): void { $this->reference = $impl; }
    public function setBankAccount(BankAccountService $impl): void { $this->bankAccount = $impl; }
    public function setCompanyPrefs(CompanyPrefsService $impl): void { $this->companyPrefs = $impl; }
    public function setCustomer(CustomerService $impl): void { $this->customer = $impl; }
    public function setExchangeRate(ExchangeRateService $impl): void { $this->exchangeRate = $impl; }
    public function setHooks(HooksService $impl): void { $this->hooks = $impl; }
    public function setTransaction(TransactionService $impl): void { $this->transaction = $impl; }
    public function setMisc(MiscService $impl): void { $this->misc = $impl; }
    public function setOrderToDelivery(OrderToDeliveryService $impl): void { $this->orderToDelivery = $impl; }

    // ── Getters (lazy default) ────────────────────────────

    public function getGlTrans(): GlTransService { return $this->glTrans ?? ($this->glTrans = new GlTransServiceNative()); }
    public function getBankTrans(): BankTransService { return $this->bankTrans ?? ($this->bankTrans = new BankTransServiceNative()); }
    public function getBankTransfer(): BankTransferService { return $this->bankTransfer ?? ($this->bankTransfer = new BankTransferServiceNative()); }
    public function getDebtorTrans(): DebtorTransService { return $this->debtorTrans ?? ($this->debtorTrans = new DebtorTransServiceNative()); }
    public function getComments(): CommentsService { return $this->comments ?? ($this->comments = new CommentsServiceNative()); }
    public function getReference(): ReferenceService { return $this->reference ?? ($this->reference = new ReferenceServiceNative()); }
    public function getBankAccount(): BankAccountService { return $this->bankAccount ?? ($this->bankAccount = new BankAccountServiceNative()); }
    public function getCompanyPrefs(): CompanyPrefsService { return $this->companyPrefs ?? ($this->companyPrefs = new CompanyPrefsServiceNative()); }
    public function getCustomer(): CustomerService { return $this->customer ?? ($this->customer = new CustomerServiceNative()); }
    public function getExchangeRate(): ExchangeRateService { return $this->exchangeRate ?? ($this->exchangeRate = new ExchangeRateServiceNative()); }
    public function getHooks(): HooksService { return $this->hooks ?? ($this->hooks = new HooksServiceNative()); }
    public function getTransaction(): TransactionService { return $this->transaction ?? ($this->transaction = new TransactionServiceNative()); }
    public function getMisc(): MiscService { return $this->misc ?? ($this->misc = new MiscServiceNative()); }
    public function getOrderToDelivery(): OrderToDeliveryService { return $this->orderToDelivery ?? ($this->orderToDelivery = new OrderToDeliveryServiceNative()); }
}
