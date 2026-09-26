<?php
declare(strict_types=1);

namespace FrontAccounting\Tests;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class FakeDbAdapter implements DbAdapterInterface
{
    /** @var array<int, array<string, mixed>> */
    /** @var array */
    private $rows;

    /** @var string|null */
    /** @var string|null */
    public $lastSql = null;
    /** @var array<mixed>|null */
    /** @var array|null */
    public $lastParams = null;
    /** @var int */
    /** @var int */
    private $insertId;
    /** @var int */
    /** @var int */
    private $affectedRows;

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
