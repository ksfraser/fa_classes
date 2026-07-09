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
        return $this->findOne(['id' => $id]);
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
        return $this->find(['report_id' => $reportId], ['name' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): PrintProfile
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
