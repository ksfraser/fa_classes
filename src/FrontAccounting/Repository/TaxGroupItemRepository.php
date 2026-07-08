<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TaxGroupItem;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TaxGroupItemRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findByTaxGroup(int $taxGroupId): array
    {
        $sql = "SELECT * FROM {$this->prefix}tax_group_items WHERE tax_group_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$taxGroupId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByTaxType(int $taxTypeId): array
    {
        $sql = "SELECT * FROM {$this->prefix}tax_group_items WHERE tax_type_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$taxTypeId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): TaxGroupItem
    {
        return new TaxGroupItem(
            (int)$row['id'],
            (int)$row['tax_group_id'],
            (int)$row['tax_type_id'],
            (float)($row['rate'] ?? 0.0)
        );
    }

    protected function getTableName(): string
    {
        return 'tax_group_items';
    }
}
