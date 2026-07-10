<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\BankTransactionRepository;
use FrontAccounting\Service\Standard\BankTransServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class BankTransServiceStandardTest extends TestCase
{
    public function testAddBankTransReturnsTrue(): void
    {
        $db = new FakeDbAdapter([], 42, 1);
        $svc = new BankTransServiceStandard(new BankTransactionRepository($db));

        $result = $svc->addBankTrans(12, 201, 1, 'PAY-001', '2026-07-10', 100.00);
        $this->assertTrue($result);
        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
        $this->assertSame(42, $db->lastInsertId());
    }

    public function testAddBankTransWithPersonType(): void
    {
        $db = new FakeDbAdapter([], 1, 1);
        $svc = new BankTransServiceStandard(new BankTransactionRepository($db));

        $result = $svc->addBankTrans(12, 201, 1, 'PAY-001', '2026-07-10', 100.00, PT_CUSTOMER, 42);
        $this->assertTrue($result);
    }

    public function testVoidBankTransDeletesRows(): void
    {
        $db = new FakeDbAdapter([
            ['id' => 10, 'type' => 12, 'trans_no' => 201, 'bank_act' => 1, 'ref' => '', 'amount' => 100.0],
            ['id' => 11, 'type' => 12, 'trans_no' => 201, 'bank_act' => 1, 'ref' => '', 'amount' => 50.0],
        ], 0, 2);
        $svc = new BankTransServiceStandard(new BankTransactionRepository($db));

        $svc->voidBankTrans(12, 201);

        $this->assertStringContainsStringIgnoringCase('delete', $db->lastSql);
    }
}
