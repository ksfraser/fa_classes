<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\CustomerBranch;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class CustomerBranchRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $branchCode, int $debtorNo): ?CustomerBranch
    {
        $sql = "SELECT * FROM {$this->prefix}cust_branch WHERE branch_code = ? AND debtor_no = ?";
        $rows = $this->db->query($sql, [$branchCode, $debtorNo]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByDebtor(int $debtorNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}cust_branch WHERE debtor_no = ? AND inactive = 0 ORDER BY br_name";
        $rows = $this->db->query($sql, [$debtorNo]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByBranchRef(string $branchRef): array
    {
        $sql = "SELECT * FROM {$this->prefix}cust_branch WHERE branch_ref = ? ORDER BY br_name";
        $rows = $this->db->query($sql, [$branchRef]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByArea(int $area): array
    {
        $sql = "SELECT * FROM {$this->prefix}cust_branch WHERE area = ? AND inactive = 0 ORDER BY br_name";
        $rows = $this->db->query($sql, [$area]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findBySalesman(int $salesman): array
    {
        $sql = "SELECT * FROM {$this->prefix}cust_branch WHERE salesman = ? AND inactive = 0 ORDER BY br_name";
        $rows = $this->db->query($sql, [$salesman]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): CustomerBranch
    {
        return new CustomerBranch(
            (int)$row['branch_code'],
            (int)$row['debtor_no'],
            (string)$row['br_name'],
            (string)$row['branch_ref'],
            (string)$row['br_address'],
            isset($row['area']) ? (int)$row['area'] : null,
            (int)$row['salesman'],
            (string)$row['default_location'],
            isset($row['tax_group_id']) ? (int)$row['tax_group_id'] : null,
            (string)$row['sales_account'],
            (string)$row['sales_discount_account'],
            (string)$row['receivables_account'],
            (string)$row['payment_discount_account'],
            (int)$row['default_ship_via'],
            (string)$row['br_post_address'],
            (int)$row['group_no'],
            (string)$row['notes'],
            isset($row['bank_account']) ? (string)$row['bank_account'] : null,
            (int)$row['inactive']
        );
    }

    protected function getTableName(): string
    {
        return 'cust_branch';
    }
}
