<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\WoIssueItem;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class WoIssueItemRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'wo_issue_items';
    public function findById(int $id): ?WoIssueItem
    {
        $sql = "SELECT * FROM {$this->prefix}wo_issue_items WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByIssue(int $issueId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_issue_items WHERE issue_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$issueId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByStockId(string $stockId): array
    {
        $sql = "SELECT * FROM {$this->prefix}wo_issue_items WHERE stock_id = ? ORDER BY date_ DESC";
        $rows = $this->db->query($sql, [$stockId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): WoIssueItem
    {
        return new WoIssueItem(
            (int)$row['id'],
            (int)$row['issue_id'],
            (string)$row['stock_id'],
            (float)$row['qty_issued'],
            (string)($row['date_'] ?? '')
        );
    }

}
