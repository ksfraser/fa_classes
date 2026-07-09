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
        return $this->findOne(['id' => $id]);
    }

    public function findByIssue(int $issueId): array
    {
        return $this->find(['issue_id' => $issueId], ['id' => 'ASC']);
    }

    public function findByStockId(string $stockId): array
    {
        return $this->find(['stock_id' => $stockId], ['date_' => 'DESC']);
    }

    protected function hydrate(array $row): WoIssueItem
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
