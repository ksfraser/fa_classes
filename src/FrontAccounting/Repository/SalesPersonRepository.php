<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesPerson;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesPersonRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $salesmanCode): ?SalesPerson
    {
        $sql = "SELECT * FROM {$this->prefix}salesman WHERE salesman_code = ?";
        $rows = $this->db->query($sql, [$salesmanCode]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}salesman WHERE salesman_name LIKE ? ORDER BY salesman_name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}salesman WHERE inactive = 0 ORDER BY salesman_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}salesman ORDER BY salesman_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): SalesPerson
    {
        return new SalesPerson(
            (int)$row['salesman_code'],
            (string)$row['salesman_name'],
            isset($row['salesman_phone']) ? (string)$row['salesman_phone'] : null,
            isset($row['salesman_fax']) ? (string)$row['salesman_fax'] : null,
            isset($row['salesman_email']) ? (string)$row['salesman_email'] : null,
            isset($row['provision']) ? (float)$row['provision'] : null,
            isset($row['break_pt']) ? (float)$row['break_pt'] : null,
            isset($row['provision2']) ? (float)$row['provision2'] : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'salesman';
    }
}
