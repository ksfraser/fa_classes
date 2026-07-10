<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Standard;

use FrontAccounting\Repository\RefsRepository;
use FrontAccounting\Service\Contracts\ReferenceService;

/**
 * @since 2026-07-10
 * Standard (DTO/Repository) implementation of ReferenceService.
 *
 * Manages refs table rows directly via RefsRepository instead of
 * delegating to FA core's $Refs->save() / check_reference().
 *
 * ┌──────────────────────────────────────────────────────────┐
 * │              ReferenceServiceStandard                     │
 * │  - refsRepo: RefsRepository                               │
 * ├──────────────────────────────────────────────────────────┤
 * │  + saveReference($type, $transNo, $ref): void            │
 * │  + checkReference($ref, $type, $transNo): bool           │
 * └──────────────────────────────────────────────────────────┘
 */
final class ReferenceServiceStandard implements ReferenceService
{
    private RefsRepository $refsRepo;

    public function __construct(RefsRepository $refsRepo)
    {
        $this->refsRepo = $refsRepo;
    }

    public function saveReference(int $type, int $transNo, string $ref): void
    {
        $this->refsRepo->insert([
            'type' => $type,
            'trans_no' => $transNo,
            'reference' => $ref,
        ]);
    }

    public function checkReference(string $ref, int $type, int $transNo = 0): bool
    {
        $existing = $this->refsRepo->findByReference($ref);
        foreach ($existing as $r) {
            if ($r->getTransNo() !== $transNo) {
                return false;
            }
        }
        return true;
    }
}
