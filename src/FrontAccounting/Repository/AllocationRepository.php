<?php

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\SupplierAllocation;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class AllocationRepository
{
    /** @var DbAdapterInterface */
    private $db;
    /** @var string */
    private $prefix;
    /** @var float */
    private $delta;

    public function __construct(DbAdapterInterface $db, float $delta = 0.005)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
        $this->delta = $delta;
    }

    public function createSupplierAllocation(SupplierAllocation $dto): void
    {
        $sql = "INSERT INTO {$this->prefix}supp_allocations
                    (amt, date_alloc, trans_type_from, trans_no_from,
                     trans_no_to, trans_type_to, person_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $dto->getAmount(),
            $dto->getDateAlloc(),
            $dto->getTransTypeFrom(),
            $dto->getTransNoFrom(),
            $dto->getTransNoTo(),
            $dto->getTransTypeTo(),
            $dto->getPersonId(),
        ]);
    }

    public function updateSupplierTransactionAllocation(int $transType, int $transNo, int $personId): void
    {
        $table = ($transType === 18) ? 'purch_orders' : 'supp_trans';
        $where = ($transType === 18)
            ? "trans.order_no = ?"
            : "trans.type = ? AND trans.trans_no = ?";

        $sql = "UPDATE {$this->prefix}{$table} trans,
                (SELECT person_id, SUM(amt) AS amt
                 FROM {$this->prefix}supp_allocations
                 WHERE person_id = ?
                   AND ((trans_type_to = ? AND trans_no_to = ?)
                     OR (trans_type_from = ? AND trans_no_from = ?))
                ) allocated
                SET trans.alloc = IFNULL(allocated.amt, 0)
                WHERE trans.supplier_id = ? AND {$where}";

        $params = [$transType, $transNo, $personId,
                   $transType, $transNo, $transType, $transNo,
                   $personId];

        $this->db->execute($sql, $params);
    }

    public function recalcSupplierAlloc(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}supp_trans st
            LEFT JOIN (
                SELECT person_id, trans_no_to AS trans_no, trans_type_to AS type,
                       SUM(amt) AS total_alloc
                FROM {$this->prefix}supp_allocations
                GROUP BY person_id, trans_no_to, trans_type_to
            ) sa_sum ON sa_sum.person_id = st.supplier_id
                   AND sa_sum.trans_no   = st.trans_no
                   AND sa_sum.type       = st.type
            SET st.alloc = COALESCE(sa_sum.total_alloc, 0)
            WHERE st.ov_amount != 0
              AND ABS(st.alloc - COALESCE(sa_sum.total_alloc, 0)) > {$d}";

        return $this->db->execute($sql);
    }

    public function recalcCustomerAlloc(): int
    {
        $d = $this->delta;
        $sql = "
            UPDATE {$this->prefix}debtor_trans dt
            LEFT JOIN (
                SELECT person_id, trans_no_to AS trans_no, trans_type_to AS type,
                       SUM(amt) AS total_alloc
                FROM {$this->prefix}cust_allocations
                GROUP BY person_id, trans_no_to, trans_type_to
            ) ca_sum ON ca_sum.person_id = dt.debtor_no
                   AND ca_sum.trans_no   = dt.trans_no
                   AND ca_sum.type       = dt.type
            SET dt.alloc = COALESCE(ca_sum.total_alloc, 0)
            WHERE dt.ov_amount != 0
              AND ABS(dt.alloc - COALESCE(ca_sum.total_alloc, 0)) > {$d}";

        return $this->db->execute($sql);
    }
}
