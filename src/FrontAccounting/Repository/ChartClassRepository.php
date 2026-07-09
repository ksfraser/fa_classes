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
        return $this->findOne(['cid' => $cid]);
    }

    public function findByType(string $ctype): array
    {
        return $this->find(['ctype' => $ctype], ['cid' => 'ASC']);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['cid' => 'ASC']);
    }

    protected function hydrate(array $row): ChartClass
    {
        return new ChartClass(
            (int)$row['cid'],
            (string)$row['name'],
            (string)$row['ctype'],
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
