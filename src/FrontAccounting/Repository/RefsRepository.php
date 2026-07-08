<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Refs;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class RefsRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByTransaction(int $type, int $transNo): ?Refs
    {
        $sql = "SELECT * FROM {$this->prefix}refs WHERE type = ? AND trans_no = ?";
        $rows = $this->db->query($sql, [$type, $transNo]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
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

    private function hydrate(array $row): Refs
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
