<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\CommentRepository;
use FrontAccounting\Service\Contracts\CommentsService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of CommentsService.
 *
 * Inserts/deletes comment rows directly via CommentRepository
 * instead of delegating to FA core's add_comments() / delete_comments().
 *
 * ┌───────────────────────────────────────────────────────────┐
 * │                CommentsServiceStandard                     │
 * │  - commentRepo: CommentRepository                         │
 * ├───────────────────────────────────────────────────────────┤
 * │  + addComments($type, $typeNo, $date_, $memo): bool       │
 * │  + deleteComments($type, $typeNo): bool                   │
 * └───────────────────────────────────────────────────────────┘
 */
final class CommentsServiceStandard implements CommentsService
{
    private CommentRepository $commentRepo;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function addComments(int $type, int $typeNo, string $date_, string $memo): bool
    {
        $this->commentRepo->insert([
            'type' => $type,
            'type_no' => $typeNo,
            'date_' => $date_,
            'memo' => $memo,
        ]);
        return true;
    }

    public function deleteComments(int $type, int $typeNo): bool
    {
        $affected = $this->commentRepo->deleteWhere([
            'type' => $type,
            'type_no' => $typeNo,
        ]);
        return $affected > 0;
    }
}
