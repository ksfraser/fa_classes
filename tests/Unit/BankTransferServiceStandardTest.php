<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Repository\GlTransRepository;
use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\BankTransferRequest;
use FrontAccounting\Service\Contracts\BankAccountService;
use FrontAccounting\Service\Contracts\GlTransService;
use FrontAccounting\Service\Contracts\TransactionService;
use FrontAccounting\Service\Standard\BankTransferServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class BankTransferServiceStandardTest extends TestCase
{
    /** @var BankAccountService&MockObject */
    private $bankAccountService;

    /** @var GlTransService&MockObject */
    private $glTransService;

    /** @var TransactionService&MockObject */
    private $transactionService;

    private FakeDbAdapter $db;
    private BankTransferServiceStandard $svc;

    protected function setUp(): void
    {
        $this->bankAccountService = $this->createMock(BankAccountService::class);
        $this->glTransService = $this->createMock(GlTransService::class);
        $this->transactionService = $this->createMock(TransactionService::class);

        $this->db = new FakeDbAdapter([]);
        $this->svc = new BankTransferServiceStandard(
            new BankTransactionRepository($this->db),
            new GlTransRepository($this->db),
            new RefsRepository($this->db),
            $this->glTransService,
            $this->bankAccountService,
            $this->transactionService
        );
    }

    public function testAddBankTransferInsertsBankTransAndRef(): void
    {
        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-001',
            memo: 'Test transfer'
        );

        $this->bankAccountService->method('getBankGlAccount')
            ->willReturnMap([[1, 1100], [2, 1200]]);

        $this->glTransService->expects($this->exactly(2))
            ->method('addGlTrans');

        $this->transactionService->expects($this->once())
            ->method('begin');
        $this->transactionService->expects($this->once())
            ->method('commit');

        $result = $this->svc->addBankTransfer($request);

        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertStringContainsStringIgnoringCase('insert', $this->db->lastSql);
    }

    public function testAddBankTransferWithCharge(): void
    {
        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-002',
            memo: 'With charge',
            charge: 2.50
        );

        $this->bankAccountService->method('getBankGlAccount')->willReturn(1100);
        $this->glTransService->method('addGlTrans')->willReturnArgument(7);
        $this->transactionService->method('begin');
        $this->transactionService->method('commit');

        $result = $this->svc->addBankTransfer($request);

        $this->assertIsInt($result);
    }

    public function testAddBankTransferWithTargetAmount(): void
    {
        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-003',
            memo: 'Cross-currency',
            charge: 0.0,
            targetAmount: 130.00
        );

        $this->bankAccountService->method('getBankGlAccount')->willReturn(1100);
        $this->glTransService->method('addGlTrans')->willReturnArgument(7);
        $this->transactionService->method('begin');
        $this->transactionService->method('commit');

        $result = $this->svc->addBankTransfer($request);
        $this->assertIsInt($result);
    }

    public function testUpdateBankTransferDelegates(): void
    {
        $request = new BankTransferRequest(
            fromBankAccount: 1,
            toBankAccount: 2,
            amount: 100.00,
            transDate: '2026-07-10',
            ref: 'BT-004',
            memo: '',
            charge: 0.0,
            targetAmount: 0.0,
            transNo: 42
        );

        $this->bankAccountService->method('getBankGlAccount')->willReturn(1100);
        $this->glTransService->method('addGlTrans')->willReturnArgument(7);
        $this->transactionService->method('begin');
        $this->transactionService->method('commit');

        $result = $this->svc->updateBankTransfer($request);
        $this->assertSame(42, $result);
    }
}
