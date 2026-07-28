<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

abstract class BaseRepository
{
    use RepositoryTrait;

    /** @var DbAdapterInterface */

    protected $db;
    /** @var string */
    protected $prefix;
    /** @var string */
    protected $tableName;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }
}
