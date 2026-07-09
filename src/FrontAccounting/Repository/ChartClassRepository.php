<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ChartClass;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ChartClassRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'chart_class';
    public function findById(int $cid): ?ChartClass
    {
        $sql = "SELECT * FROM {$this->prefix}chart_class WHERE cid = ?";
        $rows = $this->db->query($sql, [$cid]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByType(string $ctype): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_class WHERE ctype = ? ORDER BY cid";
        $rows = $this->db->query($sql, [$ctype]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_class WHERE inactive = 0 ORDER BY cid";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): ChartClass
    {
        return new ChartClass(
            (int)$row['cid'],
            (string)$row['name'],
            (string)$row['ctype'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
