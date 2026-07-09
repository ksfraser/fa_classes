<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for comment operations.
 */
interface CommentsInterface
{
    public function addComments(int $type, int $typeNo, string $date_, string $memo): bool;

    public function deleteComments(int $type, int $typeNo): bool;
}
