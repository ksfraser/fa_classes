<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CrmContact;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CrmContactRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?CrmContact
    {
        $sql = "SELECT * FROM {$this->prefix}crm_contacts WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByPerson(int $personId): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_contacts WHERE person_id = ?";
        $rows = $this->db->query($sql, [$personId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByEntity(string $type, string $action, string $entityId): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_contacts
                WHERE type = ? AND action = ? AND entity_id = ?";
        $rows = $this->db->query($sql, [$type, $action, $entityId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByType(string $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}crm_contacts WHERE type = ?";
        $rows = $this->db->query($sql, [$type]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
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

    private function hydrate(array $row): CrmContact
    {
        return new CrmContact(
            (int)$row['id'],
            (int)$row['person_id'],
            (string)$row['type'],
            (string)$row['action'],
            isset($row['entity_id']) ? (string)$row['entity_id'] : null
        );
    }

    protected function getTableName(): string
    {
        return 'crm_contacts';
    }
}
