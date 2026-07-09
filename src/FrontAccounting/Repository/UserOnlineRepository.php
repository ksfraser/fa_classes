<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\UserOnline;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class UserOnlineRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'useronline';
    public function findById(int $id): ?UserOnline
    {
        $sql = "SELECT * FROM {$this->prefix}useronline WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->prefix}useronline WHERE user_id = ? ORDER BY last_check DESC";
        $rows = $this->db->query($sql, [$userId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActiveSince(string $since): array
    {
        $sql = "SELECT * FROM {$this->prefix}useronline WHERE last_check >= ? ORDER BY last_check DESC";
        $rows = $this->db->query($sql, [$since]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): UserOnline
    {
        return new UserOnline(
            (int)$row['id'],
            (int)$row['user_id'],
            isset($row['ip_address']) ? (string)$row['ip_address'] : null,
            isset($row['time_']) ? (string)$row['time_'] : null,
            isset($row['date_']) ? (string)$row['date_'] : null,
            isset($row['curr_date']) ? (string)$row['curr_date'] : null,
            isset($row['last_check']) ? (string)$row['last_check'] : null
        );
    }

}
