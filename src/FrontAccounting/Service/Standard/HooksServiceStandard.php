<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Service\Contracts\HooksService;

/**
 * @since 2026-07-10
 * Standard no-op implementation of HooksService.
 *
 * Hooks are an FA core concept. In a pure DTO/Repository context
 * there are no hooks to fire, so both methods are no-ops.
 *
 * ┌────────────────────────────────────────────────────┐
 * │              HooksServiceStandard                   │
 * ├────────────────────────────────────────────────────┤
 * │  + preWrite($obj, $transType): void  — no-op       │
 * │  + postWrite($obj, $transType): void — no-op       │
 * └────────────────────────────────────────────────────┘
 */
final class HooksServiceStandard implements HooksService
{
    public function preWrite($obj, int $transType): void
    {
        // no-op — hooks require FA core runtime
    }

    public function postWrite($obj, int $transType): void
    {
        // no-op — hooks require FA core runtime
    }
}
