<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\ReferenceInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core reference functions.
 */
class ReferenceNative implements ReferenceInterface
{
    /**
     * Wrap $Refs->save() to persist a reference.
     */
    public function saveReference(int $type, int $transNo, string $ref): void
    {
        global $Refs;
        $Refs->save($type, $transNo, $ref);
    }

    /**
     * Wrap check_reference() — validates that a reference is unique.
     */
    public function checkReference(string $ref, int $type, int $transNo = 0): bool
    {
        return \check_reference($ref, $type, $transNo);
    }
}
