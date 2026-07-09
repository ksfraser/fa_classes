<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-09
 * Interface for hook operations.
 */
interface HooksInterface
{
    /**
     * @param mixed $obj
     */
    public function preWrite($obj, int $transType): void;

    /**
     * @param mixed $obj
     */
    public function postWrite($obj, int $transType): void;
}
