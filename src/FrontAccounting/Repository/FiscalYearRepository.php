<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\FiscalYear;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class FiscalYearRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'fiscal_year';
    public function findById(int $id): ?FiscalYear
    {
        return $this->findOne(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->find([], ['begin' => 'ASC']);
    }

    public function findActive(): ?FiscalYear
    {
        $sql = "SELECT * FROM {$this->prefix}fiscal_year WHERE closed = 0 ORDER BY begin DESC LIMIT 1";
        $rows = $this->db->query($sql);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findOpen(): array
    {
        $sql = "SELECT * FROM {$this->prefix}fiscal_year WHERE closed = 0 ORDER BY begin";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findForDate(string $date): ?FiscalYear
    {
        $sql = "SELECT * FROM {$this->prefix}fiscal_year WHERE begin <= ? AND end >= ? LIMIT 1";
        $rows = $this->db->query($sql, [$date, $date]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    protected function hydrate(array $row): FiscalYear
    {
        return new FiscalYear(
            (int)$row['id'],
            (string)$row['begin'],
            (string)$row['end'],
            (bool)(isset($row['closed']) ? (int)$row['closed'] : 0),
            (bool)(isset($row['is_active']) ? (int)$row['is_active'] : 0)
        );
    }

}
