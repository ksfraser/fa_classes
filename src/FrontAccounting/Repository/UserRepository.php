<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\User;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class UserRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'users';
    public function findById(int $id): ?User
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByUserId(string $userId): ?User
    {
        return $this->findOne(['user_id' => $userId]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['user_id' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['user_id' => 'ASC']);
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

    protected function hydrate(array $row): User
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

}
