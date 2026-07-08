<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Printer;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class PrinterRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?Printer
    {
        $sql = "SELECT * FROM {$this->prefix}printers WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
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
        $sql = "SELECT * FROM {$this->prefix}printers WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->prefix}printers ORDER BY name";
        $rows = $this->db->query($sql);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): Printer
    {
        return new Printer(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['description'],
            (string)($row['queue'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'printers';
    }
}
