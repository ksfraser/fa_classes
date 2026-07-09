<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\QuickEntryLine;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class QuickEntryLineRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'quick_entry_lines';
    public function findByQuickEntry(int $quickEntryId): array
    {
        return $this->find(['quick_entries_id' => $quickEntryId], ['id' => 'ASC']);
    }

    public function findByAccount(string $accountCode): array
    {
        return $this->find(['account_code' => $accountCode], ['id' => 'ASC']);
    }

    protected function hydrate(array $row): QuickEntryLine
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
