<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

abstract class BaseRepository
{
    use RepositoryTrait;

    protected DbAdapterInterface $db;
    protected string $prefix;
    protected string $tableName;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }
}
