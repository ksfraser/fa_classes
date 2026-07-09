<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PaymentTerm;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PaymentTermRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'payment_terms';
    public function findById(int $termsId): ?PaymentTerm
    {
        return $this->findOne(['terms_id' => $termsId]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}payment_terms WHERE terms_name LIKE ? ORDER BY terms_name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['terms_name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['terms_name' => 'ASC']);
    }

    protected function hydrate(array $row): PaymentTerm
    {
        return new PaymentTerm(
            (int)$row['terms_id'],
            (string)$row['terms_name'],
            (float)$row['days_before_due'],
            (float)$row['day_in_following_month'],
            (int)$row['terms_indicator'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
