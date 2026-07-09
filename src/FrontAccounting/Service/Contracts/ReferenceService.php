<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Service contract for reference operations.
 */
interface ReferenceService
{
    public function saveReference(int $type, int $transNo, string $ref): void;

    public function checkReference(string $ref, int $type, int $transNo = 0): bool;
}
