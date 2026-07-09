<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\DTO\CrmPerson;
use FrontAccounting\Repository\CrmPersonRepository;

final class CrmPersonService
{
    private CrmPersonRepository $personRepo;

    public function __construct(CrmPersonRepository $personRepo)
    {
        $this->personRepo = $personRepo;
    }

    public function createPerson(
        string $ref,
        string $name,
        ?string $name2 = null,
        ?string $address = null,
        ?string $phone = null,
        ?string $phone2 = null,
        ?string $fax = null,
        ?string $email = null,
        ?string $lang = null,
        string $notes = '',
        int $inactive = 0
    ): CrmPerson {
        $this->validateRequired($ref, 'ref');
        $this->validateRequired($name, 'name');

        if ($inactive !== 0 && $inactive !== 1) {
            throw new \InvalidArgumentException('inactive must be 0 or 1');
        }

        $id = $this->personRepo->insert([
            'ref' => $ref,
            'name' => $name,
            'name2' => $name2 ?? '',
            'address' => $address ?? '',
            'phone' => $phone ?? '',
            'phone2' => $phone2 ?? '',
            'fax' => $fax ?? '',
            'email' => $email ?? '',
            'lang' => $lang ?? '',
            'notes' => $notes,
            'inactive' => $inactive,
        ]);

        return new CrmPerson(
            $id,
            $ref,
            $name,
            $name2,
            $address,
            $phone,
            $phone2,
            $fax,
            $email,
            $lang,
            $notes,
            $inactive
        );
    }

    public function findById(int $id): ?CrmPerson
    {
        return $this->personRepo->findById($id);
    }

    public function findByRef(string $ref): ?CrmPerson
    {
        return $this->personRepo->findByRef($ref);
    }

    public function findByEmail(string $email): array
    {
        return $this->personRepo->findByEmail($email);
    }

    public function findActive(): array
    {
        return $this->personRepo->findActive();
    }

    private function validateRequired(string $value, string $field): void
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException("{$field} is required");
        }
    }
}
