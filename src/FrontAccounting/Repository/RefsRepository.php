<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Refs;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class RefsRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'refs';
    public function findByTransaction(int $type, int $transNo): ?Refs
    {
        return $this->findOne(['type' => $type, 'trans_no' => $transNo]);
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}refs WHERE reference LIKE ? ORDER BY type, trans_no";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByType(int $type): array
    {
        $sql = "SELECT * FROM {$this->prefix}refs WHERE type = ? ORDER BY trans_no DESC LIMIT 100";
        $rows = $this->db->query($sql, [$type]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): Refs
    {
        return new Refs(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['trans_no'],
            (string)$row['reference'],
            isset($row['description']) ? (string)$row['description'] : null
        );
    }

}
