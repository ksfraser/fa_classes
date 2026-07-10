<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

/**
 * @since 2026-07-10
 * Immutable value object for bank transfer parameters.
 *
 * Maps to the legacy fa_bank_transfer class properties:
 *   FromBankAccount, ToBankAccount, amount, charge,
 *   target_amount, ref, memo_, trans_date, trans_no.
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │                  BankTransferRequest                      │
 * │  - transNo: ?int              (null for new transfers)   │
 * │  - fromBankAccount: int                                  │
 * │  - toBankAccount: int                                    │
 * │  - amount: float                                         │
 * │  - charge: float                                         │
 * │  - targetAmount: float                                   │
 * │  - ref: string                                           │
 * │  - memo: string                                          │
 * │  - transDate: string                                     │
 * └──────────────────────────────────────────────────────────┘
 */
final class BankTransferRequest
{
    private ?int $transNo;
    private int $fromBankAccount;
    private int $toBankAccount;
    private float $amount;
    private float $charge;
    private float $targetAmount;
    private string $ref;
    private string $memo;
    private string $transDate;

    public function __construct(
        int $fromBankAccount,
        int $toBankAccount,
        float $amount,
        string $transDate,
        string $ref,
        string $memo = '',
        float $charge = 0.0,
        float $targetAmount = 0.0,
        ?int $transNo = null
    ) {
        $this->fromBankAccount = $fromBankAccount;
        $this->toBankAccount = $toBankAccount;
        $this->amount = $amount;
        $this->transDate = $transDate;
        $this->ref = $ref;
        $this->memo = $memo;
        $this->charge = $charge;
        $this->targetAmount = $targetAmount;
        $this->transNo = $transNo;
    }

    public function getTransNo(): ?int { return $this->transNo; }
    public function getFromBankAccount(): int { return $this->fromBankAccount; }
    public function getToBankAccount(): int { return $this->toBankAccount; }
    public function getAmount(): float { return $this->amount; }
    public function getCharge(): float { return $this->charge; }
    public function getTargetAmount(): float { return $this->targetAmount; }
    public function getRef(): string { return $this->ref; }
    public function getMemo(): string { return $this->memo; }
    public function getTransDate(): string { return $this->transDate; }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'trans_no' => $this->transNo,
            'from_bank_account' => $this->fromBankAccount,
            'to_bank_account' => $this->toBankAccount,
            'amount' => $this->amount,
            'charge' => $this->charge,
            'target_amount' => $this->targetAmount,
            'ref' => $this->ref,
            'memo' => $this->memo,
            'trans_date' => $this->transDate,
        ];
    }
}
