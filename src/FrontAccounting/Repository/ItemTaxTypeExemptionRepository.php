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
        return $this->find(['item_tax_type_id' => $itemTaxTypeId]);
    }

    public function findByTaxType(int $taxTypeId): array
    {
        return $this->find(['tax_type_id' => $taxTypeId]);
    }

    public function exists(int $itemTaxTypeId, int $taxTypeId): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}item_tax_type_exemptions WHERE item_tax_type_id = ? AND tax_type_id = ?";
        $rows = $this->db->query($sql, [$itemTaxTypeId, $taxTypeId]);
        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    protected function hydrate(array $row): ItemTaxTypeExemption
    {
        return new ItemTaxTypeExemption(
            (int)$row['id'],
            (int)$row['item_tax_type_id'],
            (int)$row['tax_type_id']
        );
    }

}
