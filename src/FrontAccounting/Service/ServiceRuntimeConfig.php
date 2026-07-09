<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\Service\Contracts\BankAccountInterface;
use FrontAccounting\Service\Contracts\BankTransInterface;
use FrontAccounting\Service\Contracts\CommentsInterface;
use FrontAccounting\Service\Contracts\CompanyPrefsInterface;
use FrontAccounting\Service\Contracts\CustomerInterface;
use FrontAccounting\Service\Contracts\DebtorTransInterface;
use FrontAccounting\Service\Contracts\ExchangeRateInterface;
use FrontAccounting\Service\Contracts\GlTransInterface;
use FrontAccounting\Service\Contracts\HooksInterface;
use FrontAccounting\Service\Contracts\MiscInterface;
use FrontAccounting\Service\Contracts\ReferenceInterface;
use FrontAccounting\Service\Contracts\TransactionInterface;
use FrontAccounting\Service\Native\BankAccountNative;
use FrontAccounting\Service\Native\BankTransNative;
use FrontAccounting\Service\Native\CommentsNative;
use FrontAccounting\Service\Native\CompanyPrefsNative;
use FrontAccounting\Service\Native\CustomerNative;
use FrontAccounting\Service\Native\DebtorTransNative;
use FrontAccounting\Service\Native\ExchangeRateNative;
use FrontAccounting\Service\Native\GlTransNative;
use FrontAccounting\Service\Native\HooksNative;
use FrontAccounting\Service\Native\MiscNative;
use FrontAccounting\Service\Native\ReferenceNative;
use FrontAccounting\Service\Native\TransactionNative;

/**
 * @since 2026-07-09
 * Runtime configuration for service Native vs DTO/Repository mode.
 *
 * Defaults to MODE_NATIVE, which creates Native wrapper instances
 * lazily.  Switch to MODE_DTO and inject specific interface
 * implementations via the set*Dto() methods to replace individual
 * FA core functions with DTO/Repository-based equivalents.
 *
 * ┌───────────────────────────────────────────────────────────┐
 * │                     ServiceRuntimeConfig                  │
 * │  mode: 'native' | 'dto'                                   │
 * │                                                           │
 * │  Lazy Native (private)    DTO Overrides (public setters)  │
 * │  ─────────────────────    ──────────────────────────────  │
 * │  glTransNative            setGlTransDto()                 │
 * │  bankTransNative          setBankTransDto()               │
 * │  debtorTransNative        setDebtorTransDto()             │
 * │  commentsNative           setCommentsDto()                │
 * │  referenceNative          setReferenceDto()               │
 * │  bankAccountNative        setBankAccountDto()             │
 * │  companyPrefsNative       setCompanyPrefsDto()            │
 * │  customerNative           setCustomerDto()                │
 * │  exchangeRateNative       setExchangeRateDto()            │
 * │  hooksNative              setHooksDto()                   │
 * │  transactionNative        setTransactionDto()             │
 * │  miscNative               setMiscDto()                    │
 * ├───────────────────────────────────────────────────────────┤
 * │  get*() returns the DTO implementation when mode='dto'    │
 * │  and a DTO override is set; otherwise returns Native.     │
 * └───────────────────────────────────────────────────────────┘
 */
class ServiceRuntimeConfig
{
    public const MODE_NATIVE = 'native';
    public const MODE_DTO = 'dto';

    private string $mode;

    private ?GlTransInterface $glTransNative = null;
    private ?BankTransInterface $bankTransNative = null;
    private ?DebtorTransInterface $debtorTransNative = null;
    private ?CommentsInterface $commentsNative = null;
    private ?ReferenceInterface $referenceNative = null;
    private ?BankAccountInterface $bankAccountNative = null;
    private ?CompanyPrefsInterface $companyPrefsNative = null;
    private ?CustomerInterface $customerNative = null;
    private ?ExchangeRateInterface $exchangeRateNative = null;
    private ?HooksInterface $hooksNative = null;
    private ?TransactionInterface $transactionNative = null;
    private ?MiscInterface $miscNative = null;

    private ?GlTransInterface $glTransDto = null;
    private ?BankTransInterface $bankTransDto = null;
    private ?DebtorTransInterface $debtorTransDto = null;
    private ?CommentsInterface $commentsDto = null;
    private ?ReferenceInterface $referenceDto = null;
    private ?BankAccountInterface $bankAccountDto = null;
    private ?CompanyPrefsInterface $companyPrefsDto = null;
    private ?CustomerInterface $customerDto = null;
    private ?ExchangeRateInterface $exchangeRateDto = null;
    private ?HooksInterface $hooksDto = null;
    private ?TransactionInterface $transactionDto = null;
    private ?MiscInterface $miscDto = null;

    public function __construct(string $mode = self::MODE_NATIVE)
    {
        $this->mode = $mode;
    }

    // -----------------------------------------------------------------
    //  DTO overrides — set these to replace individual Native wrappers
    // -----------------------------------------------------------------

    public function setGlTransDto(GlTransInterface $impl): void { $this->glTransDto = $impl; }
    public function setBankTransDto(BankTransInterface $impl): void { $this->bankTransDto = $impl; }
    public function setDebtorTransDto(DebtorTransInterface $impl): void { $this->debtorTransDto = $impl; }
    public function setCommentsDto(CommentsInterface $impl): void { $this->commentsDto = $impl; }
    public function setReferenceDto(ReferenceInterface $impl): void { $this->referenceDto = $impl; }
    public function setBankAccountDto(BankAccountInterface $impl): void { $this->bankAccountDto = $impl; }
    public function setCompanyPrefsDto(CompanyPrefsInterface $impl): void { $this->companyPrefsDto = $impl; }
    public function setCustomerDto(CustomerInterface $impl): void { $this->customerDto = $impl; }
    public function setExchangeRateDto(ExchangeRateInterface $impl): void { $this->exchangeRateDto = $impl; }
    public function setHooksDto(HooksInterface $impl): void { $this->hooksDto = $impl; }
    public function setTransactionDto(TransactionInterface $impl): void { $this->transactionDto = $impl; }
    public function setMiscDto(MiscInterface $impl): void { $this->miscDto = $impl; }

    // -----------------------------------------------------------------
    //  Factory getters — return DTO impl in DTO mode when set,
    //  otherwise lazily create and return Native
    // -----------------------------------------------------------------

    public function getGlTrans(): GlTransInterface
    {
        if ($this->mode === self::MODE_DTO && $this->glTransDto !== null) {
            return $this->glTransDto;
        }
        if ($this->glTransNative === null) {
            $this->glTransNative = new GlTransNative();
        }
        return $this->glTransNative;
    }

    public function getBankTrans(): BankTransInterface
    {
        if ($this->mode === self::MODE_DTO && $this->bankTransDto !== null) {
            return $this->bankTransDto;
        }
        if ($this->bankTransNative === null) {
            $this->bankTransNative = new BankTransNative();
        }
        return $this->bankTransNative;
    }

    public function getDebtorTrans(): DebtorTransInterface
    {
        if ($this->mode === self::MODE_DTO && $this->debtorTransDto !== null) {
            return $this->debtorTransDto;
        }
        if ($this->debtorTransNative === null) {
            $this->debtorTransNative = new DebtorTransNative();
        }
        return $this->debtorTransNative;
    }

    public function getComments(): CommentsInterface
    {
        if ($this->mode === self::MODE_DTO && $this->commentsDto !== null) {
            return $this->commentsDto;
        }
        if ($this->commentsNative === null) {
            $this->commentsNative = new CommentsNative();
        }
        return $this->commentsNative;
    }

    public function getReference(): ReferenceInterface
    {
        if ($this->mode === self::MODE_DTO && $this->referenceDto !== null) {
            return $this->referenceDto;
        }
        if ($this->referenceNative === null) {
            $this->referenceNative = new ReferenceNative();
        }
        return $this->referenceNative;
    }

    public function getBankAccount(): BankAccountInterface
    {
        if ($this->mode === self::MODE_DTO && $this->bankAccountDto !== null) {
            return $this->bankAccountDto;
        }
        if ($this->bankAccountNative === null) {
            $this->bankAccountNative = new BankAccountNative();
        }
        return $this->bankAccountNative;
    }

    public function getCompanyPrefs(): CompanyPrefsInterface
    {
        if ($this->mode === self::MODE_DTO && $this->companyPrefsDto !== null) {
            return $this->companyPrefsDto;
        }
        if ($this->companyPrefsNative === null) {
            $this->companyPrefsNative = new CompanyPrefsNative();
        }
        return $this->companyPrefsNative;
    }

    public function getCustomer(): CustomerInterface
    {
        if ($this->mode === self::MODE_DTO && $this->customerDto !== null) {
            return $this->customerDto;
        }
        if ($this->customerNative === null) {
            $this->customerNative = new CustomerNative();
        }
        return $this->customerNative;
    }

    public function getExchangeRate(): ExchangeRateInterface
    {
        if ($this->mode === self::MODE_DTO && $this->exchangeRateDto !== null) {
            return $this->exchangeRateDto;
        }
        if ($this->exchangeRateNative === null) {
            $this->exchangeRateNative = new ExchangeRateNative();
        }
        return $this->exchangeRateNative;
    }

    public function getHooks(): HooksInterface
    {
        if ($this->mode === self::MODE_DTO && $this->hooksDto !== null) {
            return $this->hooksDto;
        }
        if ($this->hooksNative === null) {
            $this->hooksNative = new HooksNative();
        }
        return $this->hooksNative;
    }

    public function getTransaction(): TransactionInterface
    {
        if ($this->mode === self::MODE_DTO && $this->transactionDto !== null) {
            return $this->transactionDto;
        }
        if ($this->transactionNative === null) {
            $this->transactionNative = new TransactionNative();
        }
        return $this->transactionNative;
    }

    public function getMisc(): MiscInterface
    {
        if ($this->mode === self::MODE_DTO && $this->miscDto !== null) {
            return $this->miscDto;
        }
        if ($this->miscNative === null) {
            $this->miscNative = new MiscNative();
        }
        return $this->miscNative;
    }
}
