<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Attachment;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class AttachmentRepository {
    use RepositoryTrait;
    private DbAdapterInterface $db;
    private string $prefix;

    public function __construct(DbAdapterInterface $db)
    {
        $this->db = $db;
        $this->prefix = $db->getTablePrefix();
    }

    public function findById(int $id): ?Attachment
    {
        $sql = "SELECT * FROM {$this->prefix}attachments WHERE id = ?";
        $rows = $this->db->query($sql, [$id]);
        if (empty($rows)) return null;
        return $this->hydrate($rows[0]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}attachments WHERE type = ? AND type_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$type, $typeNo]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByFilename(string $filename): array
    {
        $sql = "SELECT * FROM {$this->prefix}attachments WHERE filename LIKE ? ORDER BY id";
        $rows = $this->db->query($sql, ['%' . $filename . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    private function hydrate(array $row): Attachment
    {
        return new Attachment(
            (int)$row['id'],
            (int)$row['type'],
            (int)$row['type_no'],
            (string)$row['filename'],
            isset($row['file_type']) ? (string)$row['file_type'] : null,
            (int)$row['file_size'],
            (string)$row['content'],
            isset($row['description']) ? (string)$row['description'] : null,
            isset($row['date_']) ? (string)$row['date_'] : null
        );
    }

    protected function getTableName(): string
    {
        return 'attachments';
    }
}
