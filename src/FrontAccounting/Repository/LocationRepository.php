<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Location;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class LocationRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'locations';
    public function findByCode(string $locCode): ?Location
    {
        return $this->findOne(['loc_code' => $locCode]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['location_name' => 'ASC']);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}locations WHERE location_name LIKE ? ORDER BY location_name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findDefault(): ?Location
    {
        $sql = "SELECT * FROM {$this->prefix}locations WHERE dflt = 1 LIMIT 1";
        $rows = $this->db->query($sql);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findAll(): array
    {
        return $this->find([], ['location_name' => 'ASC']);
    }

    protected function hydrate(array $row): Location
    {
        return new Location(
            (string)$row['loc_code'],
            (string)$row['location_name'],
            isset($row['delivery_address']) ? (string)$row['delivery_address'] : null,
            isset($row['delivery_phone']) ? (string)$row['delivery_phone'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0),
            (bool)(isset($row['dflt']) ? (int)$row['dflt'] : 0),
            isset($row['tax_group_id']) ? (int)$row['tax_group_id'] : 0
        );
    }

}
