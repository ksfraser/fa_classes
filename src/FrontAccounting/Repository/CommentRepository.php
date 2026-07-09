<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Comment;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CommentRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'comments';
    public function findById(int $id): ?Comment
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        return $this->find(['type' => $type, 'type_no' => $typeNo], ['id' => 'ASC']);
    }

    public function findByUser(string $userEmail): array
    {
        return $this->find(['user_email' => $userEmail], ['date_' => 'DESC']);
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

    protected function hydrate(array $row): Comment
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
