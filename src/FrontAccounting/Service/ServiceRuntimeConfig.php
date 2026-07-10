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
 * │  Registry (set / get)      Default (lazy ??=)            │
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
    private ?GlTransService $glTrans = null;
    private ?BankTransService $bankTrans = null;
    private ?BankTransferService $bankTransfer = null;
    private ?DebtorTransService $debtorTrans = null;
    private ?CommentsService $comments = null;
    private ?ReferenceService $reference = null;
    private ?BankAccountService $bankAccount = null;
    private ?CompanyPrefsService $companyPrefs = null;
    private ?CustomerService $customer = null;
    private ?ExchangeRateService $exchangeRate = null;
    private ?HooksService $hooks = null;
    private ?TransactionService $transaction = null;
    private ?MiscService $misc = null;

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

    // ── Getters (lazy ??= default) ────────────────────────────

    public function getGlTrans(): GlTransService { return $this->glTrans ??= new GlTransServiceNative(); }
    public function getBankTrans(): BankTransService { return $this->bankTrans ??= new BankTransServiceNative(); }
    public function getBankTransfer(): BankTransferService { return $this->bankTransfer ??= new BankTransferServiceNative(); }
    public function getDebtorTrans(): DebtorTransService { return $this->debtorTrans ??= new DebtorTransServiceNative(); }
    public function getComments(): CommentsService { return $this->comments ??= new CommentsServiceNative(); }
    public function getReference(): ReferenceService { return $this->reference ??= new ReferenceServiceNative(); }
    public function getBankAccount(): BankAccountService { return $this->bankAccount ??= new BankAccountServiceNative(); }
    public function getCompanyPrefs(): CompanyPrefsService { return $this->companyPrefs ??= new CompanyPrefsServiceNative(); }
    public function getCustomer(): CustomerService { return $this->customer ??= new CustomerServiceNative(); }
    public function getExchangeRate(): ExchangeRateService { return $this->exchangeRate ??= new ExchangeRateServiceNative(); }
    public function getHooks(): HooksService { return $this->hooks ??= new HooksServiceNative(); }
    public function getTransaction(): TransactionService { return $this->transaction ??= new TransactionServiceNative(); }
    public function getMisc(): MiscService { return $this->misc ??= new MiscServiceNative(); }
}
