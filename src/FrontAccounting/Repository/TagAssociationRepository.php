<?php

declare(strict_types=1);

namespace FrontAccounting\Repository;

use FrontAccounting\DTO\TagAssociation;
use Ksfraser\ModulesDAO\Db\DbAdapterInterface;

final class TagAssociationRepository extends \FrontAccounting\Repository\BaseRepository
{
    protected string $tableName = 'tag_associations';
    public function findByTag(int $tagId): array
    {
        $sql = "SELECT * FROM {$this->prefix}tag_associations WHERE tag_id = ? ORDER BY id";
        $rows = $this->db->query($sql, [$tagId]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function findByTransaction(int $transType, int $transNo): array
    {
        $sql = "SELECT * FROM {$this->prefix}tag_associations WHERE trans_type = ? AND trans_no = ? ORDER BY id";
        $rows = $this->db->query($sql, [$transType, $transNo]);
        $results = [];
        foreach ($rows as $row) $results[] = $this->hydrate($row);
        return $results;
    }

    public function exists(int $tagId, int $transType, int $transNo): bool
    {
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->prefix}tag_associations WHERE tag_id = ? AND trans_type = ? AND trans_no = ?";
        $rows = $this->db->query($sql, [$tagId, $transType, $transNo]);
        return !empty($rows) && (int)$rows[0]['cnt'] > 0;
    }

    private function hydrate(array $row): TagAssociation
    {
        return new TagAssociation(
            (int)$row['id'],
            (int)$row['tag_id'],
            (int)$row['trans_type'],
            (int)$row['trans_no']
        );
    }

}
