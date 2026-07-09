<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CrmPerson;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CrmPersonRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'crm_persons';
    public function findById(int $id): ?CrmPerson
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByRef(string $ref): ?CrmPerson
    {
        return $this->findOne(['ref' => $ref]);
    }

    public function findByEmail(string $email): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_persons WHERE email = ? AND inactive = 0";
        $rows = $this->db->query($sql, [$email]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    public function search(string $query): array
    {
        $like = '%' . $query . '%';
        $sql = "SELECT * FROM {$this->prefix}crm_persons
                WHERE (name LIKE ? OR ref LIKE ? OR email LIKE ?)
                  AND inactive = 0
                ORDER BY name LIMIT 50";
        $rows = $this->db->query($sql, [$like, $like, $like]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    protected function hydrate(array $row): CrmPerson
    {
        return new CrmPerson(
            (int)$row['id'],
            (string)$row['ref'],
            (string)$row['name'],
            isset($row['name2']) ? (string)$row['name2'] : null,
            isset($row['address']) ? (string)$row['address'] : null,
            isset($row['phone']) ? (string)$row['phone'] : null,
            isset($row['phone2']) ? (string)$row['phone2'] : null,
            isset($row['fax']) ? (string)$row['fax'] : null,
            isset($row['email']) ? (string)$row['email'] : null,
            isset($row['lang']) ? (string)$row['lang'] : null,
            (string)$row['notes'],
            (int)$row['inactive']
        );
    }

}
