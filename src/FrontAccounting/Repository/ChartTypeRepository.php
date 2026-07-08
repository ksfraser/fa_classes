<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\ChartType;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class ChartTypeRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?ChartType
    {
        $sql = "SELECT * FROM {$this->prefix}chart_types WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate($rows[0]);
    }

    public function findByClass(int $classId): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_types WHERE class_id = ? ORDER BY name";
        $rows = $this->db->query($sql, [$classId]);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_types WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->prefix}chart_types WHERE inactive = 0 ORDER BY name";
        $rows = $this->db->query($sql);

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): ChartType
    {
        return new ChartType(
            (int)$row['id'],
            (string)$row['name'],
            (int)$row['class_id'],
            isset($row['parent']) ? ($row['parent'] !== '' ? (int)$row['parent'] : null) : null,
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

    protected function getTableName(): string
    {
        return 'chart_types';
    }
}
