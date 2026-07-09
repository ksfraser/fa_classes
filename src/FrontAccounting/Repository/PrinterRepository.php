<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Printer;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PrinterRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'printers';
    public function findById(int $id): ?Printer
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): ?Printer
    {
        $sql = "SELECT * FROM {$this->prefix}printers WHERE name LIKE ? LIMIT 1";
        $rows = $this->db->query($sql, ['%' . $name . '%']);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): Printer
    {
        return new Printer(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['description'],
            (string)($row['queue'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
