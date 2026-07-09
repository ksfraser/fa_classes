<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ItemTaxTypeExemption;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ItemTaxTypeExemptionRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'item_tax_type_exemptions';
    public function findByItemTaxType(int $itemTaxTypeId): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_tax_type_exemptions WHERE item_tax_type_id = ?";
        $rows = $this->db->query($sql, [$itemTaxTypeId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByTaxType(int $taxTypeId): array
    {
        $sql = "SELECT * FROM {$this->prefix}item_tax_type_exemptions WHERE tax_type_id = ?";
        $rows = $this->db->query($sql, [$taxTypeId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function exists(int $itemTaxTypeId, int $taxTypeId): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}item_tax_type_exemptions WHERE item_tax_type_id = ? AND tax_type_id = ?";
        $rows = $this->db->query($sql, [$itemTaxTypeId, $taxTypeId]);
        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    private function hydrate(array $row): ItemTaxTypeExemption
    {
        return new ItemTaxTypeExemption(
            (int)$row['id'],
            (int)$row['item_tax_type_id'],
            (int)$row['tax_type_id']
        );
    }

}
