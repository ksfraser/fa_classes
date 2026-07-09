<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Tag;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TagRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'tags';
    public function findById(int $id): ?Tag
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $sql = "SELECT * FROM {$this->prefix}tags WHERE name LIKE ? ORDER BY name";
        $rows = $this->db->query($sql, ['%' . $name . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findActive(): array
    {
        return $this->find(['inactive' => 0], ['name' => 'ASC']);
    }

    public function findAll(): array
    {
        return $this->find([], ['name' => 'ASC']);
    }

    protected function hydrate(array $row): Tag
    {
        return new Tag(
            (int)$row['id'],
            (string)$row['name'],
            (string)($row['description'] ?? ''),
            (bool)(isset($row['inactive']) ? (int)$row['inactive'] : 0)
        );
    }

}
