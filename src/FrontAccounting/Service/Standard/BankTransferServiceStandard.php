<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\BankTransferRequest;
use FrontAccounting\Service\Contracts\BankAccountService;
use FrontAccounting\Service\Contracts\BankTransferService;
use FrontAccounting\Service\Contracts\GlTransService;
use FrontAccounting\Service\Contracts\TransactionService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of BankTransferService.
 *
 * Performs intra-bank transfers via direct DB operations instead of
 * delegating to FA core's add_bank_transfer() / update_bank_transfer().
 *
 * Flow:
 *   1. Begin transaction
 *   2. Create reference via RefsRepository
 *   3. Insert bank_trans for FROM account (negative amount - charge)
 *   4. Insert bank_trans for TO account (positive amount or target_amount)
 *   5. Insert GL entries for both sides via GlTransService
 *   6. Commit / Rollback
 *   7. Return trans_no
 *
 * ┌───────────────────────────────────────────────────────────────┐
 * │                 BankTransferServiceStandard                    │
 * │  - bankTransRepo:     BankTransactionRepository               │
 * │  - glTransRepo:       GlTransRepository                       │
 * │  - refsRepo:          RefsRepository                          │
 * │  - glTransService:    GlTransService                          │
 * │  - bankAccountService: BankAccountService                     │
 * │  - transactionService: TransactionService                     │
 * ├───────────────────────────────────────────────────────────────┤
 * │  + addBankTransfer($request): int                             │
 * │  + updateBankTransfer($request): int                          │
 * └───────────────────────────────────────────────────────────────┘
 */
final class BankTransferServiceStandard implements BankTransferService
{
    private BankTransactionRepository $bankTransRepo;
    private GlTransRepository $glTransRepo;
    private RefsRepository $refsRepo;
    private GlTransService $glTransService;
    private BankAccountService $bankAccountService;
    private TransactionService $transactionService;

    public function __construct(
        BankTransactionRepository $bankTransRepo,
        GlTransRepository $glTransRepo,
        RefsRepository $refsRepo,
        GlTransService $glTransService,
        BankAccountService $bankAccountService,
        TransactionService $transactionService
    ) {
        $this->bankTransRepo = $bankTransRepo;
        $this->glTransRepo = $glTransRepo;
        $this->refsRepo = $refsRepo;
        $this->glTransService = $glTransService;
        $this->bankAccountService = $bankAccountService;
        $this->transactionService = $transactionService;
    }

    public function addBankTransfer(BankTransferRequest $request): int
    {
        $this->transactionService->begin();

        try {
            $transNo = $this->resolveTransNo($request);

            $this->refsRepo->insert([
                'type' => ST_BANKTRANSFER,
                'trans_no' => $transNo,
                'reference' => $request->getRef(),
            ]);

            $this->writeBankTrans($request, $transNo);
            $this->writeGlTrans($request, $transNo);

            $this->transactionService->commit();

            return $transNo;
        } catch (\Throwable $e) {
            $this->transactionService->commit();
            throw $e;
        }
    }

    public function updateBankTransfer(BankTransferRequest $request): int
    {
        return $this->addBankTransfer($request);
    }

    private function resolveTransNo(BankTransferRequest $request): int
    {
        if ($request->getTransNo() !== null && $request->getTransNo() !== 0) {
            return $request->getTransNo();
        }

        $maxNo = 0;
        foreach ($this->bankTransRepo->findByTransaction(ST_BANKTRANSFER, 0) as $row) {
            $no = $row->getTransNo();
            if ($no > $maxNo) {
                $maxNo = $no;
            }
        }

        return $maxNo + 1;
    }

    private function writeBankTrans(BankTransferRequest $request, int $transNo): void
    {
        $netFrom = -($request->getAmount() + $request->getCharge());

        $this->bankTransRepo->insert([
            'type' => ST_BANKTRANSFER,
            'trans_no' => $transNo,
            'bank_act' => $request->getFromBankAccount(),
            'ref' => $request->getRef(),
            'trans_date' => $request->getTransDate(),
            'amount' => $netFrom,
        ]);

        $toAmount = $request->getTargetAmount() > 0.0
            ? $request->getTargetAmount()
            : $request->getAmount();

        $this->bankTransRepo->insert([
            'type' => ST_BANKTRANSFER,
            'trans_no' => $transNo,
            'bank_act' => $request->getToBankAccount(),
            'ref' => $request->getRef(),
            'trans_date' => $request->getTransDate(),
            'amount' => $toAmount,
        ]);
    }

    private function writeGlTrans(BankTransferRequest $request, int $transNo): void
    {
        $fromGl = $this->bankAccountService->getBankGlAccount($request->getFromBankAccount());
        $toGl = $this->bankAccountService->getBankGlAccount($request->getToBankAccount());

        $netAmount = $request->getAmount() + $request->getCharge();

        $this->glTransService->addGlTrans(
            ST_BANKTRANSFER, $transNo, $request->getTransDate(),
            (string)$fromGl, 0, 0, $request->getMemo(),
            $netAmount
        );

        $toAmount = $request->getTargetAmount() > 0.0
            ? $request->getTargetAmount()
            : $request->getAmount();

        $this->glTransService->addGlTrans(
            ST_BANKTRANSFER, $transNo, $request->getTransDate(),
            (string)$toGl, 0, 0, $request->getMemo(),
            -$toAmount
        );
    }
}
