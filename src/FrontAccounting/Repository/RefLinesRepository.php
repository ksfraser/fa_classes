<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\RefLines;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class RefLinesRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'reflines';
    public function findByType(int $type): array
    {
        return $this->find(['type' => $type], ['reference' => 'ASC']);
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}reflines WHERE reference LIKE ? ORDER BY type";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['type' => 'ASC', 'reference' => 'ASC']);
    }

    protected function hydrate(array $row): RefLines
    {
        return new RefLines(
            (int)$row['id'],
            (int)$row['type'],
            (string)$row['reference'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
