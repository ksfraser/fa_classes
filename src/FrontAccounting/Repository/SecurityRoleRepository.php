<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SecurityRole;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SecurityRoleRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'security_roles';
    public function findById(int $id): ?SecurityRole
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByRole(string $role): array
    {
        $sql = "SELECT * FROM {$this->prefix}security_roles WHERE role LIKE ? ORDER BY role";
        $rows = $this->db->query($sql, ['%' . $role . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['role' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['role' => 'ASC']);
    }

    protected function hydrate(array $row): SecurityRole
    {
        return new SecurityRole(
            (int)$row['id'],
            (string)$row['role'],
            (string)$row['description'],
            (string)($row['sections'] ?? ''),
            (string)($row['areas'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
