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
        $sql = "SELECT * FROM {$this->prefix}security_roles WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}security_roles WHERE inactive = 0 ORDER BY role";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}security_roles ORDER BY role";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): SecurityRole
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
