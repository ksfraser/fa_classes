<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CreditStatus;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CreditStatusRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'credit_status';
    public function findById(int $id): ?CreditStatus
    {
        return $this->findOne(['id' => $id]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['reason_description' => 'ASC']);
    }

    public function findDissallowInvoices(): array
    {
        $sql = "SELECT * FROM {$this->prefix}credit_status WHERE dissallow_invoices = 1 AND inactive = 0 ORDER BY reason_description";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        return $this->find([], ['reason_description' => 'ASC']);
    }

    protected function hydrate(array $row): CreditStatus
    {
        return new CreditStatus(
            (int)$row['id'],
            (string)$row['reason_description'],
            (bool)(isset($row['dissallow_invoices']) ? (int)$row['dissallow_invoices'] : 0),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
