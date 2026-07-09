<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CrmContact;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CrmContactRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'crm_contacts';
    public function findById(int $id): ?CrmContact
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByPerson(int $personId): array
    {
        return $this->find(['person_id' => $personId]);
    }

    public function findByEntity(string $type, string $action, string $entityId): array
    {
        return $this->find(['type' => $type, 'action' => $action, 'entity_id' => $entityId]);
    }

    public function findByType(string $type): array
    {
        return $this->find(['type' => $type]);
    }

    public function findPersonContacts(int $personId): array
    {
        $sql = "SELECT c.*, p.name AS person_name, p.email AS person_email
                FROM {$this->prefix}crm_contacts c
                JOIN {$this->prefix}crm_persons p ON p.id = c.person_id
                WHERE c.person_id = ?";
        $rows = $this->db->query($sql, [$personId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    protected function hydrate(array $row): CrmContact
    {
        return new CrmContact(
            (int)$row['id'],
            (int)$row['person_id'],
            (string)$row['type'],
            (string)$row['action'],
            isset($row['entity_id']) ? (string)$row['entity_id'] : null
        );
    }

}
