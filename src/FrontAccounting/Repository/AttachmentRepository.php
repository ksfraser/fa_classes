<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\Attachment;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class AttachmentRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'attachments';
    public function findById(int $id): ?Attachment
    {
        return $this->findOne(['id' => $id]);
    }

    public function findByTransaction(int $type, int $typeNo): array
    {
        return $this->find(['type' => $type, 'type_no' => $typeNo], ['id' => 'ASC']);
    }

    public function findByFilename(string $filename): array
    {
        $sql = "SELECT * FROM {$this->prefix}attachments WHERE filename LIKE ? ORDER BY id";
        $rows = $this->db->query($sql, ['%' . $filename . '%']);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    protected function hydrate(array $row): Attachment
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

}
