<?php

declare(strict_types=1);

namespace Tests\Comparison;

use FrontAccounting\Repository\CommentRepository;
use FrontAccounting\Service\Native\CommentsServiceNative;
use FrontAccounting\Service\Standard\CommentsServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CommentsServiceComparisonTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../vendor/ksfraser/famock/php/FAMock.php';
    }

    public function testAddCommentsBothReturnTrue(): void
    {
        // famock: add_comments(...) returns true
        $native = new CommentsServiceNative();
        $db = new FakeDbAdapter([], 1, 1);
        $standard = new CommentsServiceStandard(new CommentRepository($db));

        $nativeResult = $native->addComments(12, 201, '2026-07-10', 'Test memo');
        $standardResult = $standard->addComments(12, 201, '2026-07-10', 'Test memo');

        $this->assertSame($nativeResult, $standardResult);
    }

    public function testDeleteCommentsBothReturnTrue(): void
    {
        // famock: delete_comments(...) returns true
        $native = new CommentsServiceNative();

        // Standard returns true when affected rows > 0
        $db = new FakeDbAdapter([], 0, 2);
        $standard = new CommentsServiceStandard(new CommentRepository($db));

        $nativeResult = $native->deleteComments(12, 201);
        $standardResult = $standard->deleteComments(12, 201);

        $this->assertSame($nativeResult, $standardResult);
    }
}
