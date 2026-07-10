<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Service\Native\BankTransServiceNative;
use FrontAccounting\Service\Standard\BankTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class BankTransServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testAddBankTransBothReturnTrue(): void
    {
        // famock: add_bank_trans(...) returns true
        $native = new BankTransServiceNative();
        $db = new FakeDbAdapter([], 1, 1);
        $standard = new BankTransServiceStandard(new BankTransactionRepository($db));

        $nativeResult = $native->addBankTrans(12, 201, 1, 'PAY-001', '2026-07-10', 100.00);
        $standardResult = $standard->addBankTrans(12, 201, 1, 'PAY-001', '2026-07-10', 100.00);

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testAddBankTransWithPersonTypeBothReturnTrue(): void
    {
        $native = new BankTransServiceNative();
        $db = new FakeDbAdapter([], 1, 1);
        $standard = new BankTransServiceStandard(new BankTransactionRepository($db));

        $nativeResult = $native->addBankTrans(
            12, 201, 1, 'PAY-001', '2026-07-10', 100.00, PT_CUSTOMER, 42
        );
        $standardResult = $standard->addBankTrans(
            12, 201, 1, 'PAY-001', '2026-07-10', 100.00, PT_CUSTOMER, 42
        );

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testVoidBankTransDoesNotThrow(): void
    {
        $native = new BankTransServiceNative();
        $standardDb = new FakeDbAdapter([
            ['id' => 10, 'type' => 12, 'trans_no' => 201, 'bank_act' => 1, 'ref' => '', 'amount' => 100.0],
        ], 0, 1);
        $standard = new BankTransServiceStandard(new BankTransactionRepository($standardDb));

        $native->voidBankTrans(12, 201);
        $standard->voidBankTrans(12, 201);

        $this->expectNotToPerformAssertions();
    }
}
