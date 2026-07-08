<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CrmPerson
{
    private int $id;
    private string $ref;
    private string $name;
    private ?string $name2;
    private ?string $address;
    private ?string $phone;
    private ?string $phone2;
    private ?string $fax;
    private ?string $email;
    private ?string $lang;
    private string $notes;
    private int $inactive;

    public function __construct(
        int $id,
        string $ref,
        string $name,
        ?string $name2,
        ?string $address,
        ?string $phone,
        ?string $phone2,
        ?string $fax,
        ?string $email,
        ?string $lang,
        string $notes,
        int $inactive
    ) {
        $this->id = $id;
        $this->ref = $ref;
        $this->name = $name;
        $this->name2 = $name2;
        $this->address = $address;
        $this->phone = $phone;
        $this->phone2 = $phone2;
        $this->fax = $fax;
        $this->email = $email;
        $this->lang = $lang;
        $this->notes = $notes;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getRef(): string { return $this->ref; }
    public function getName(): string { return $this->name; }
    public function getName2(): ?string { return $this->name2; }
    public function getAddress(): ?string { return $this->address; }
    public function getPhone(): ?string { return $this->phone; }
    public function getPhone2(): ?string { return $this->phone2; }
    public function getFax(): ?string { return $this->fax; }
    public function getEmail(): ?string { return $this->email; }
    public function getLang(): ?string { return $this->lang; }
    public function getNotes(): string { return $this->notes; }
    public function getInactive(): int { return $this->inactive; }
}
