<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\QuickEntryLine;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class QuickEntryLineRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByQuickEntry(int $quickEntryId): array
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entry_lines WHERE quick_entries_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$quickEntryId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByAccount(string $accountCode): array
    {
        $sql = "SELECT * FROM {$this->prefix}quick_entry_lines WHERE account_code = ? ORDER BY id";
        $rows = $this->db->query($sql, [$accountCode]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): QuickEntryLine
    {
        return new QuickEntryLine(
            (int)$row['id'],
            (int)$row['quick_entries_id'],
            (string)$row['account_code'],
            (string)$row['action'],
            (string)($row['amount'] ?? '0'),
            (string)($row['memo_'] ?? '')
        );
    }
}
