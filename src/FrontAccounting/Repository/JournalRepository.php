<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Journal;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class JournalRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'journal';
    public function findById(int $type, int $typeNo): ?Journal
    {
        return $this->findOne(['type' => $type, 'type_no' => $typeNo]);
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}journal WHERE reference LIKE ? ORDER BY tran_date DESC";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}journal WHERE tran_date >= ? AND tran_date <= ? ORDER BY tran_date, type_no";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findRecurrent(): array
    {
        $sql = "SELECT * FROM {$this->prefix}journal WHERE recurrent = 1 ORDER BY tran_date DESC";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): Journal
    {
        return new Journal(
            (int)$row['type'],
            (int)$row['type_no'],
            (string)$row['tran_date'],
            (string)$row['reference'],
            (string)$row['memo_'],
            isset($row['event_date']) ? (string)$row['event_date'] : null,
            (bool)(isset($row['recurrent']) ? (int)$row['recurrent'] : 0),
            (int)($row['recurring'] ?? 0),
            isset($row['user_id']) ? (int)$row['user_id'] : null
        );
    }

}
