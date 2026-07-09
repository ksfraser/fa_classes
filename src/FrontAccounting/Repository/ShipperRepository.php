<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Shipper;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ShipperRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'shippers';
    public function findById(int $shipperId): ?Shipper
    {
        return $this->findOne(['shipper_id' => $shipperId]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}shippers WHERE shipper_name LIKE ? ORDER BY shipper_name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['shipper_name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['shipper_name' => 'ASC']);
    }

    protected function hydrate(array $row): Shipper
    {
        return new Shipper(
            (int)$row['shipper_id'],
            (string)$row['shipper_name'],
            (string)($row['contact'] ?? ''),
            (string)($row['phone'] ?? ''),
            (string)($row['phone2'] ?? ''),
            (string)($row['email'] ?? ''),
            (string)($row['website'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
