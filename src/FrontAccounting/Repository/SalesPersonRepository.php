<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SalesPerson;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class SalesPersonRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'salesman';
    public function findById(int $salesmanCode): ?SalesPerson
    {
        return $this->findOne(['salesman_code' => $salesmanCode]);
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
        return $this->find(['inactive' => 0], ['salesman_name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['salesman_name' => 'ASC']);
    }

    protected function hydrate(array $row): SalesPerson
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

}
