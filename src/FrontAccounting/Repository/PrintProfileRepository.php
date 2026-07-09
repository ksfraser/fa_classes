<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PrintProfile;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PrintProfileRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'print_profiles';
    public function findById(int $id): ?PrintProfile
    {
        $sql = "SELECT * FROM {$this->prefix}print_profiles WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}print_profiles WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByReport(int $reportId): array
    {
        $sql = "SELECT * FROM {$this->prefix}print_profiles WHERE report_id = ? ORDER BY name";
        $rows = $this->db->query($sql, [$reportId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}print_profiles WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): PrintProfile
    {
        return new PrintProfile(
            (int)$row['id'],
            (string)$row['name'],
            (int)$row['report_id'],
            (string)($row['printer_name'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
