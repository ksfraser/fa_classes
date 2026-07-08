<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\GrnBatch;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class GrnBatchRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?GrnBatch
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByPurchOrder(int $purchOrderNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE purch_order_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$purchOrderNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByReference(string $reference): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE reference LIKE ? ORDER BY id DESC";
        $rows = $this->db->query($sql, ['%' . $reference . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByLocation(string $location): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE loc_code = ? ORDER BY id DESC";
        $rows = $this->db->query($sql, [$location]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findReceived(): array
    {
        $sql = "SELECT * FROM {$this->prefix}grn_batch WHERE is_received = 1 ORDER BY id DESC";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): GrnBatch
    {
        return new GrnBatch(
            (int)$row['id'],
            (int)$row['purch_order_no'],
            isset($row['reference']) ? (string)$row['reference'] : null,
            isset($row['ord_date']) ? (string)$row['ord_date'] : null,
            isset($row['delivery_date']) ? (string)$row['delivery_date'] : null,
            isset($row['due_date']) ? (string)$row['due_date'] : null,
            isset($row['loc_code']) ? (string)$row['loc_code'] : '',
            (bool)(isset($row['is_received']) ? (int)$row['is_received'] : 0),
            (bool)(isset($row['is_partial']) ? (int)$row['is_partial'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'grn_batch';
    }
}
