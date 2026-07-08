<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\PaymentTerm;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PaymentTermRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $termsId): ?PaymentTerm
    {
        $sql = "SELECT * FROM {$this->prefix}payment_terms WHERE terms_id = ?";
        $rows = $this->db->query($sql, [$termsId]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}payment_terms WHERE inactive = 0 ORDER BY terms_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}payment_terms ORDER BY terms_name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): PaymentTerm
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

    protected function getTableName(): string
    {
        return 'payment_terms';
    }
}
