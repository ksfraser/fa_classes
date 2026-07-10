<?php

declare(strict_types=1);

namespace Tests\Unit;

use FrontAccounting\Repository\CommentRepository;
use FrontAccounting\Service\Standard\CommentsServiceStandard;
use FrontAccounting\Tests\FakeDbAdapter;
use PHPUnit\Framework\TestCase;

final class CommentsServiceStandardTest extends TestCase
{
    public function testAddCommentsReturnsTrue(): void
    {
        $db = new FakeDbAdapter([]);
        $svc = new CommentsServiceStandard(new CommentRepository($db));

        $result = $svc->addComments(12, 201, '2026-07-10', 'Test memo');

        $this->assertTrue($result);
        $this->assertStringContainsStringIgnoringCase('insert', $db->lastSql);
    }

    public function testDeleteCommentsReturnsTrueWhenRowsAffected(): void
    {
        $db = new FakeDbAdapter([], 0, 2);
        $svc = new CommentsServiceStandard(new CommentRepository($db));

        $result = $svc->deleteComments(12, 201);

        $this->assertTrue($result);
        $this->assertStringContainsStringIgnoringCase('delete', $db->lastSql);
    }

    public function testDeleteCommentsReturnsFalseWhenNoRows(): void
    {
        $db = new FakeDbAdapter([], 0, 0);
        $svc = new CommentsServiceStandard(new CommentRepository($db));

        $result = $svc->deleteComments(12, 999);

        $this->assertFalse($result);
    }
}
