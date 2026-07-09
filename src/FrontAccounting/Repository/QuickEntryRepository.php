<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\QuickEntry;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class QuickEntryRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'quick_entries';
    public function findById(int $id): ?QuickEntry
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByType(int $type): array
    {
        return $this->find(['type' => $type], ['description' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['description' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['description' => 'ASC']);
    }

    protected function hydrate(array $row): QuickEntry
    {
        return new QuickEntry(
            (int)$row['id'],
            (string)$row['description'],
            (int)$row['type'],
            (int)$row['base_amount'],
            (string)($row['base_amount_type'] ?? ''),
            (string)($row['base_desc'] ?? '')
        );
    }

}
