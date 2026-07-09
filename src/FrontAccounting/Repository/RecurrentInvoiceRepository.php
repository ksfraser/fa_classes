<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\RecurrentInvoice;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class RecurrentInvoiceRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'recurrent_invoices';
    public function findById(int $id): ?RecurrentInvoice
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByDebtor(int $debtorNo): array
    {
        return $this->find(['debtor_no' => $debtorNo], ['description' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['description' => 'ASC']);
    }

    public function findDue(string $asAtDate): array
    {
        $sql = "SELECT * FROM {$this->prefix}recurrent_invoices WHERE inactive = 0 AND end_date >= ? OR end_date IS NULL ORDER BY description";
        $rows = $this->db->query($sql, [$asAtDate]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): RecurrentInvoice
    {
        return new RecurrentInvoice(
            (int)$row['id'],
            (string)$row['description'],
            (int)$row['order_no'],
            (int)$row['debtor_no'],
            (int)$row['branch_code'],
            (int)$row['group_'],
            (int)$row['sales_type'],
            (string)$row['date_'],
            isset($row['end_date']) ? (string)$row['end_date'] : null,
            (int)($row['template_no'] ?? 0),
            (int)($row['is_template'] ?? 0),
            (string)($row['memo_'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
