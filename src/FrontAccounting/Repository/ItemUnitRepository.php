<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemUnit;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemUnitRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'item_units';
    public function findByAbbreviation(string $abbreviation): ?ItemUnit
    {
        $sql = "SELECT * FROM {$this->prefix}item_units WHERE abbr = ?";
        $rows = $this->db->query($sql, [$abbreviation]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_units ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_units WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): ItemUnit
    {
        return new ItemUnit(
            (string)$row['abbr'],
            (string)$row['name'],
            (int)($row['decimals'] ?? 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
