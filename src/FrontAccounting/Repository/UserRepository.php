<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\User;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class UserRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByUserId(string $userId): ?User
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE user_id = ?";
        $rows = $this->db->query($sql, [$userId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE inactive = 0 ORDER BY user_id";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}users ORDER BY user_id";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM {$this->prefix}users WHERE email = ? LIMIT 1";
        $rows = $this->db->query($sql, [$email]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    private function hydrate(array $row): User
    {
        return new User(
            (int)$row['id'],
            (string)$row['user_id'],
            isset($row['real_name']) ? (string)$row['real_name'] : null,
            (string)($row['email'] ?? ''),
            isset($row['phone']) ? (string)$row['phone'] : null,
            isset($row['language']) ? (int)$row['language'] : null,
            isset($row['date_format']) ? (string)$row['date_format'] : null,
            (bool)(isset($row['show_hints']) ? (int)$row['show_hints'] : 1),
            (bool)(isset($row['show_graphic']) ? (int)$row['show_graphic'] : 1),
            isset($row['query_size']) ? (string)$row['query_size'] : null,
            (bool)(isset($row['show_currency']) ? (int)$row['show_currency'] : 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'users';
    }
}
