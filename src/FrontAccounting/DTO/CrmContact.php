<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class CrmContact
{
    private int $id;
    private int $personId;
    private string $type;
    private string $action;
    private ?string $entityId;

    public function __construct(
        int $id,
        int $personId,
        string $type,
        string $action,
        ?string $entityId
    ) {
        $this->id = $id;
        $this->personId = $personId;
        $this->type = $type;
        $this->action = $action;
        $this->entityId = $entityId;
    }

    public function getId(): int { return $this->id; }
    public function getPersonId(): int { return $this->personId; }
    public function getType(): string { return $this->type; }
    public function getAction(): string { return $this->action; }
    public function getEntityId(): ?string { return $this->entityId; }
}
