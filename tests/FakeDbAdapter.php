<?php
declare(strict_types=1);

namespace FrontAccounting\Tests;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class FakeDbAdapter implements DbAdapterInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $rows;

    /** @var string|null */
    public ?string $lastSql = null;
    /** @var array<mixed>|null */
    public ?array $lastParams = null;
    /** @var int */
    private int $insertId;
    /** @var int */
    private int $affectedRows;

    /** @param array<int, array<string, mixed>> $rows */
    public function __construct(array $rows = [], int $insertId = 1, int $affectedRows = 0)
    {
        $this->rows = $rows;
        $this->insertId = $insertId;
        $this->affectedRows = $affectedRows;
    }

    public function getDialect(): string
    {
        return 'mysql';
    }

    public function getTablePrefix(): string
    {
        return '0_';
    }

    public function escape(string $value): string
    {
        return addslashes($value);
    }

    public function query(string $sql, array $params = []): array
    {
        $this->lastSql = $sql;
        $this->lastParams = $params;
        return $this->rows;
    }

    public function execute(string $sql, array $params = []): int
    {
        $this->lastSql = $sql;
        $this->lastParams = $params;
        return $this->affectedRows;
    }

    public function lastInsertId(): ?int
    {
        return $this->insertId;
    }
}
