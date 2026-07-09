<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\DTO\CrmContact;
use FrontAccounting\Repository\CrmContactRepository;

/**
 * @since 2026-07-09
 * Service for managing CRM contact links.
 *
 * Links a CRM person to an entity (customer, branch, etc.)
 * with a specific type and action.
 *
 * ┌───────────────────────────────────────────────────────┐
 * │                    CrmContactService                   │
 * │  - contactRepo: CrmContactRepository                   │
 * ├───────────────────────────────────────────────────────┤
 * │  + createContact(): CrmContact                        │
 * │  + findById($id): ?CrmContact                         │
 * │  + findByPerson($personId): CrmContact[]              │
 * │  + findByEntity($type, $action, $entityId): CrmContact│
 * │  + findPersonContacts($personId): CrmContact[]        │
 * ├───────────────────────────────────────────────────────┤
 * │ Creates and retrieves CRM contact links via the        │
 * │ DTO/Repository layer with basic validation.            │
 * └───────────────────────────────────────────────────────┘
 */
final class CrmContactService
{
    private CrmContactRepository $contactRepo;

    public function __construct(CrmContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }

    public function createContact(
        int $personId,
        string $type,
        string $action,
        ?string $entityId = null
    ): CrmContact {
        if ($personId <= 0) {
            throw new \InvalidArgumentException('personId must be positive');
        }
        $this->validateRequired($type, 'type');
        $this->validateRequired($action, 'action');

        $id = $this->contactRepo->insert([
            'person_id' => (string)$personId,
            'type' => $type,
            'action' => $action,
            'entity_id' => $entityId ?? '',
        ]);

        return new CrmContact($id, $personId, $type, $action, $entityId);
    }

    public function findById(int $id): ?CrmContact
    {
        return $this->contactRepo->findById($id);
    }

    public function findByPerson(int $personId): array
    {
        return $this->contactRepo->findByPerson($personId);
    }

    public function findByEntity(string $type, string $action, string $entityId): array
    {
        return $this->contactRepo->findByEntity($type, $action, $entityId);
    }

    public function findPersonContacts(int $personId): array
    {
        return $this->contactRepo->findPersonContacts($personId);
    }

    private function validateRequired(string $value, string $field): void
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException("{$field} is required");
        }
    }
}
