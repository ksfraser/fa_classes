<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Native;

use FrontAccounting\Service\Contracts\HooksInterface;

/**
 * @since 2026-07-09
 * Native wrapper for FA core hook functions.
 */
class HooksNative implements HooksInterface
{
    /**
     * Wrap hook_db_prewrite().
     *
     * @param mixed $obj
     */
    public function preWrite($obj, int $transType): void
    {
        \hook_db_prewrite($obj, $transType);
    }

    /**
     * Wrap hook_db_postwrite().
     *
     * @param mixed $obj
     */
    public function postWrite($obj, int $transType): void
    {
        \hook_db_postwrite($obj, $transType);
    }
}
