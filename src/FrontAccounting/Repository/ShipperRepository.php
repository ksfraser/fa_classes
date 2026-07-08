<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Shipper;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ShipperRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $shipperId): ?Shipper
    {
        $sql = "SELECT * FROM {$this->prefix}shippers WHERE shipper_id = ?";
        $rows = $this->db->query($sql, [$shipperId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}shippers WHERE inactive = 0 ORDER BY shipper_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}shippers ORDER BY shipper_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Shipper
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

    protected function getTableName(): string
    {
        return 'shippers';
    }
}
