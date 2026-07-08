<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\PaginatedResult;
use PHPUnit\Framework\TestCase;

final class PaginatedResultTest extends TestCase
{
    public function testFirstPage(): void
    {
        $r = new PaginatedResult(['a', 'b'], 10, 1, 5);
        $this->assertSame(['a', 'b'], $r->getItems());
        $this->assertSame(10, $r->getTotal());
        $this->assertSame(1, $r->getPage());
        $this->assertSame(5, $r->getPerPage());
        $this->assertSame(2, $r->getTotalPages());
        $this->assertTrue($r->hasNext());
        $this->assertFalse($r->hasPrevious());
        $this->assertSame(1, $r->getFrom());
        $this->assertSame(5, $r->getTo());
    }

    public function testMiddlePage(): void
    {
        $r = new PaginatedResult(['c', 'd'], 15, 2, 5);
        $this->assertTrue($r->hasNext());
        $this->assertTrue($r->hasPrevious());
        $this->assertSame(6, $r->getFrom());
        $this->assertSame(10, $r->getTo());
    }

    public function testLastPage(): void
    {
        $r = new PaginatedResult([], 10, 2, 5);
        $this->assertFalse($r->hasNext());
        $this->assertTrue($r->hasPrevious());
    }

    public function testEmptyResult(): void
    {
        $r = new PaginatedResult([], 0, 1, 25);
        $this->assertSame(0, $r->getTotalPages());
        $this->assertFalse($r->hasNext());
        $this->assertFalse($r->hasPrevious());
        $this->assertSame(1, $r->getFrom());
        $this->assertSame(0, $r->getTo());
    }

    public function testSinglePage(): void
    {
        $r = new PaginatedResult(['x'], 3, 1, 25);
        $this->assertSame(1, $r->getTotalPages());
        $this->assertFalse($r->hasNext());
        $this->assertFalse($r->hasPrevious());
    }

    public function testExactFit(): void
    {
        $r = new PaginatedResult(['a', 'b'], 10, 2, 5);
        $this->assertSame(2, $r->getTotalPages());
        $this->assertFalse($r->hasNext());
        $this->assertSame(6, $r->getFrom());
        $this->assertSame(10, $r->getTo());
    }
}
