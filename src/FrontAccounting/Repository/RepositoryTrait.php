<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PaginatedResult;
use Ksfraser\ModulesDAO\Sql\QueryBuilder;

trait RepositoryTrait
{
    protected function getTableName(): string
    {
        if (!isset($this->tableName)) {
            throw new \BadMethodCallException(static::class . ' must set $tableName or override getTableName()');
        }
        return $this->tableName;
    }

    public function findWhere(array $conditions, array $orderBy = [], ?int $limit = null, ?int $offset = null): array
    {
        $qb = new QueryBuilder($this->db);
        $qb->from($this->getTableName());

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $qb->where($column, $value[0], $value[1] ?? null);
            } else {
                $qb->where($column, $value);
            }
        }

        foreach ($orderBy as $column => $direction) {
            $qb->orderBy($column, $direction);
        }

        if ($limit !== null) {
            $qb->limit($limit);
        }

        if ($offset !== null) {
            $qb->offset($offset);
        }

        return $qb->get();
    }

    public function findOneWhere(array $conditions): ?array
    {
        $rows = $this->findWhere($conditions, [], 1);
        return $rows[0] ?? null;
    }

    public function countWhere(array $conditions): int
    {
        $qb = new QueryBuilder($this->db);
        $qb->from($this->getTableName());

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $qb->where($column, $value[0], $value[1] ?? null);
            } else {
                $qb->where($column, $value);
            }
        }

        return $qb->count();
    }

    public function paginate(array $conditions, int $page = 1, int $perPage = 25, array $orderBy = []): PaginatedResult
    {
        $total = $this->countWhere($conditions);

        $qb = new QueryBuilder($this->db);
        $qb->from($this->getTableName());

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $qb->where($column, $value[0], $value[1] ?? null);
            } else {
                $qb->where($column, $value);
            }
        }

        foreach ($orderBy as $column => $direction) {
            $qb->orderBy($column, $direction);
        }

        $qb->page($page, $perPage);
        $items = $qb->get();

        return new PaginatedResult($items, $total, $page, $perPage);
    }

    public function find(array $conditions, array $orderBy = [], ?int $limit = null, ?int $offset = null): array
    {
        $rows = $this->findWhere($conditions, $orderBy, $limit, $offset);
        return array_map([$this, 'hydrate'], $rows);
    }

    public function findOne(array $conditions): ?object
    {
        $row = $this->findOneWhere($conditions);
        return $row !== null ? $this->hydrate($row) : null;
    }

    public function existsWhere(array $conditions): bool
    {
        return $this->countWhere($conditions) > 0;
    }

    public function deleteWhere(array $conditions): int
    {
        $qb = (new QueryBuilder($this->db))->delete($this->getTableName());

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $qb->where($column, $value[0], $value[1] ?? null);
            } else {
                $qb->where($column, $value);
            }
        }

        return $qb->execute();
    }

    public function insert(array $data): int
    {
        $qb = (new QueryBuilder($this->db))->insert($this->getTableName(), $data);
        $qb->execute();
        return $this->db->lastInsertId();
    }

    public function update(array $data, array $conditions): int
    {
        $qb = (new QueryBuilder($this->db))->update($this->getTableName(), $data);

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $qb->where($column, $value[0], $value[1] ?? null);
            } else {
                $qb->where($column, $value);
            }
        }

        return $qb->execute();
    }
}
