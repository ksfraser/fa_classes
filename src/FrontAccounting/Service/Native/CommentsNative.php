<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\CommentsInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core comment functions.
 */
class CommentsNative implements CommentsInterface
{
    /**
     * Wrap add_comments().
     */
    public function addComments(int $type, int $typeNo, string $date_, string $memo): bool
    {
        return (bool)\add_comments($type, $typeNo, $date_, $memo);
    }

    /**
     * Wrap delete_comments().
     */
    public function deleteComments(int $type, int $typeNo): bool
    {
        return \delete_comments($type, $typeNo);
    }
}
