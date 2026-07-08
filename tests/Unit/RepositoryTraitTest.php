<?php
declare(strict_types=1);

namespace FrontAccounting\Tests\Unit;

use FrontAccounting\DTO\PaginatedResult;
use FrontAccounting\Repository\RepositoryTrait;
use FrontAccounting\Tests\FakeDbAdapter;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;
use PHPUnit\Framework\TestCase;

final class RepositoryTraitTest extends TestCase
{
    public function testFindWhereSimple(): void
    {
        $db = new FakeDbAdapter([['id' => '1', 'name' => 'Alice']]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $rows = $repo->findWhere(['active' => 1]);

        $this->assertCount(1, $rows);
        $this->assertSame('Alice', $rows[0]['name']);
        $this->assertStringContainsString('FROM 0_users WHERE active = ?', $db->lastSql);
        $this->assertSame([1], $db->lastParams);
    }

    public function testFindWhereWithOperator(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $repo->findWhere(['age' => ['>=', 18]]);

        $this->assertStringContainsString('WHERE age >= ?', $db->lastSql);
        $this->assertSame([18], $db->lastParams);
    }

    public function testFindWhereWithIn(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $repo->findWhere(['role' => ['IN', ['admin', 'user']]]);

        $this->assertStringContainsString('WHERE role IN (?, ?)', $db->lastSql);
        $this->assertSame(['admin', 'user'], $db->lastParams);
    }

    public function testFindWhereWithOrderBy(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $repo->findWhere(['active' => 1], ['name' => 'ASC', 'id' => 'DESC']);

        $this->assertStringContainsString('ORDER BY name ASC, id DESC', $db->lastSql);
    }

    public function testFindWhereWithLimitOffset(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $repo->findWhere([], [], 10, 20);

        $this->assertStringContainsString('LIMIT 20, 10', $db->lastSql);
    }

    public function testFindOneWhereReturnsFirstRow(): void
    {
        $db = new FakeDbAdapter([['id' => '1', 'name' => 'Alice']]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $row = $repo->findOneWhere(['id' => 1]);

        $this->assertNotNull($row);
        $this->assertSame('Alice', $row['name']);
        $this->assertStringContainsString('LIMIT 1', $db->lastSql);
    }

    public function testFindOneWhereReturnsNull(): void
    {
        $db = new FakeDbAdapter([]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $this->assertNull($repo->findOneWhere(['id' => 999]));
    }

    public function testCountWhere(): void
    {
        $db = new FakeDbAdapter([['cnt' => '42']]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $count = $repo->countWhere(['active' => 1]);

        $this->assertSame(42, $count);
        $this->assertStringContainsString('COUNT(*)', $db->lastSql);
        $this->assertStringContainsString('active = ?', $db->lastSql);
    }

    public function testExistsWhereTrue(): void
    {
        $db = new FakeDbAdapter([['cnt' => '1']]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $this->assertTrue($repo->existsWhere(['id' => 1]));
    }

    public function testExistsWhereFalse(): void
    {
        $db = new FakeDbAdapter([['cnt' => '0']]);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $this->assertFalse($repo->existsWhere(['id' => 999]));
    }

    public function testDeleteWhere(): void
    {
        $db = new FakeDbAdapter([], 0, 2);
        $repo = new class($db) {
            use RepositoryTrait;
            private DbAdapterInterface $db;
            public function __construct(DbAdapterInterface $db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $affected = $repo->deleteWhere(['inactive' => 1]);

        $this->assertSame(2, $affected);
        $this->assertStringContainsString('DELETE FROM 0_users', $db->lastSql);
        $this->assertStringContainsString('inactive = ?', $db->lastSql);
        $this->assertSame([1], $db->lastParams);
    }

    public function testPaginate(): void
    {
        $db = new class implements DbAdapterInterface {
            public int $callCount = 0;
            public ?string $lastSql = null;
            public ?array $lastParams = null;
            public function getDialect(): string { return 'mysql'; }
            public function getTablePrefix(): string { return '0_'; }
            public function escape(string $value): string { return addslashes($value); }
            public function query(string $sql, array $params = []): array {
                $this->callCount++;
                $this->lastSql = $sql;
                $this->lastParams = $params;
                if ($this->callCount === 1) {
                    return [['cnt' => '25']];
                }
                $rows = [];
                for ($i = 1; $i <= 10; $i++) {
                    $rows[] = ['id' => (string)($i + 10), 'name' => 'User ' . ($i + 10)];
                }
                return $rows;
            }
            public function execute(string $sql, array $params = []): int { $this->lastSql = $sql; $this->lastParams = $params; return 0; }
            public function lastInsertId(): ?int { return null; }
        };

        $repo = new class($db) {
            use RepositoryTrait;
            private $db;
            public function __construct($db) { $this->db = $db; }
            protected function getTableName(): string { return 'users'; }
        };

        $result = $repo->paginate(['active' => 1], 2, 10, ['name' => 'ASC']);

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertSame(25, $result->getTotal());
        $this->assertSame(2, $result->getPage());
        $this->assertSame(10, $result->getPerPage());
        $this->assertSame(3, $result->getTotalPages());
        $this->assertCount(10, $result->getItems());
    }
}
