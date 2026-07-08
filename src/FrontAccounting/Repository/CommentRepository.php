<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Comment;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CommentRepository
{
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?Comment
    {
        $sql = "SELECT * FROM {$this->prefix}comments WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}comments WHERE type = ? AND type_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$type, $typeNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByUser(string $userEmail): array
    {
        $sql = "SELECT * FROM {$this->prefix}comments WHERE user_email = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$userEmail]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findForDateRange(string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}comments WHERE date_ >= ? AND date_ <= ? ORDER BY date_, id";
        $rows = $this->db->query($sql, [$fromDate, $toDate]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Comment
    {
        return new Comment(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['type_no'],
            isset($row['date_']) ? (string)$row['date_'] : null,
            (string)($row['memo'] ?? ''),
            isset($row['user_email']) ? (string)$row['user_email'] : null
        );
    }
}
