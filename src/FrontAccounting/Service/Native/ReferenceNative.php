<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

/**
 * Native wrapper for FA core reference functions.
 *
 * Wraps $Refs->save() and check_reference(). The $Refs global is
 * an instance of the FA references class.
 */
class ReferenceNative
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
