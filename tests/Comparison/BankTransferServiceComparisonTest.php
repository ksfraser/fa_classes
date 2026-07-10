<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\BankTransferRequest;
use FrontAccounting\Service\Contracts\BankAccountService;
use FrontAccounting\Service\Contracts\GlTransService;
use FrontAccounting\Service\Contracts\TransactionService;
use FrontAccounting\Service\Native\BankTransferServiceNative;
use FrontAccounting\Service\Standard\BankTransferServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class BankTransferServiceComparisonTest extends TestCase
{
    /** @var BankAccountService&MockObject */
    private $bankAccountService;

    /** @var GlTransService&MockObject */
    private $glTransService;

    /** @var TransactionService&MockObject */
    private $transactionService;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';

        $this->bankAccountService = $this->createMock(BankAccountService::class);
        $this->glTransService = $this->createMock(GlTransService::class);
        $this->transactionService = $this->createMock(TransactionService::class);
    }

    public function testAddBankTransferBothReturnInt(): void
    {
        // famock: add_bank_transfer(...) returns 300, 301, ...
        $native = new BankTransferServiceNative();

        $this->bankAccountService->method('getBankGlAccount')->willReturn(1100);
        $this->glTransService->method('addGlTrans')->willReturnArgument(7);
        $this->transactionService->method('begin');
        $this->transactionService->method('commit');

        $db = new FakeDbAdapter([]);
        $standard = new BankTransferServiceStandard(
            new BankTransactionRepository($db),
            new GlTransRepository($db),
            new RefsRepository($db),
            $this->glTransService,
            $this->bankAccountService,
            $this->transactionService
        );

        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-001'
        );

        $nativeResult = $native->addBankTransfer($request);
        $standardResult = $standard->addBankTransfer($request);

        $this->assertIsInt($nativeResult);
        $this->assertIsInt($standardResult);
    }

    public function testUpdateBankTransferBothReturnInt(): void
    {
        $native = new BankTransferServiceNative();

        $this->bankAccountService->method('getBankGlAccount')->willReturn(1100);
        $this->glTransService->method('addGlTrans')->willReturnArgument(7);
        $this->transactionService->method('begin');
        $this->transactionService->method('commit');

        $db = new FakeDbAdapter([]);
        $standard = new BankTransferServiceStandard(
            new BankTransactionRepository($db),
            new GlTransRepository($db),
            new RefsRepository($db),
            $this->glTransService,
            $this->bankAccountService,
            $this->transactionService
        );

        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-002',
            transNo: 50
        );

        $nativeResult = $native->updateBankTransfer($request);
        $standardResult = $standard->updateBankTransfer($request);

        $this->assertIsInt($nativeResult);
        $this->assertIsInt($standardResult);
    }
}
