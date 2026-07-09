<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SysPrefs;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SysPrefsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'sys_prefs';
    public function findByName(string $name): ?SysPrefs
    {
        $sql = "SELECT * FROM {$this->prefix}sys_prefs WHERE name = ?";
        $rows = $this->db->query($sql, [$name]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByCategory(int $category): array
    {
        $sql = "SELECT * FROM {$this->prefix}sys_prefs WHERE category = ? ORDER BY name";
        $rows = $this->db->query($sql, [$category]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->prefix}sys_prefs WHERE user_id = ? ORDER BY name";
        $rows = $this->db->query($sql, [$userId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByCompany(int $companyId): array
    {
        $sql = "SELECT * FROM {$this->prefix}sys_prefs WHERE company_id = ? ORDER BY name";
        $rows = $this->db->query($sql, [$companyId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findGlobal(): array
    {
        $sql = "SELECT * FROM {$this->prefix}sys_prefs WHERE user_id IS NULL AND company_id IS NULL ORDER BY name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): SysPrefs
    {
        return new SysPrefs(
            (string)$row['name'],
            (string)$row['value'],
            isset($row['description']) ? (string)$row['description'] : null,
            (int)($row['category'] ?? 0),
            (int)($row['type'] ?? 0),
            (int)($row['length'] ?? 0),
            isset($row['user_id']) ? (int)$row['user_id'] : null,
            isset($row['company_id']) ? (int)$row['company_id'] : null
        );
    }

}
