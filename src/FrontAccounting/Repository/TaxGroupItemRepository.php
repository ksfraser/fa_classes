<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TaxGroupItem;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TaxGroupItemRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'tax_group_items';
    public function findByTaxGroup(int $taxGroupId): array
    {
        return $this->find(['tax_group_id' => $taxGroupId], ['id' => 'ASC']);
    }

    public function findByTaxType(int $taxTypeId): array
    {
        return $this->find(['tax_type_id' => $taxTypeId], ['id' => 'ASC']);
    }

    protected function hydrate(array $row): TaxGroupItem
    {
        return new TaxGroupItem(
            (int)$row['id'],
            (int)$row['tax_group_id'],
            (int)$row['tax_type_id'],
            (float)($row['rate'] ?? 0.0)
        );
    }

}
