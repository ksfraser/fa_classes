<?php

declare(strict_types=1);

namespace FrontAccounting\Service;

use FrontAccounting\Repository\CommentRepository;
use FrontAccounting\Repository\RefsRepository;

final class ReferenceService
{
    private RefsRepository $refsRepo;
    private CommentRepository $commentRepo;

    public function __construct(RefsRepository $refsRepo, CommentRepository $commentRepo)
    {
        $this->refsRepo = $refsRepo;
        $this->commentRepo = $commentRepo;
    }

    public function getNextReference(int $type): string
    {
        $existing = $this->refsRepo->findByType($type);

        if (empty($existing)) {
            return (string)$type . '-001';
        }

        $maxRef = '';
        foreach ($existing as $ref) {
            $refStr = $ref->getReference();
            if ($refStr > $maxRef) {
                $maxRef = $refStr;
            }
        }

        if ($maxRef === '') {
            return (string)$type . '-001';
        }

        $prefix = (string)$type . '-';
        if (strpos($maxRef, $prefix) === 0) {
            $num = (int)substr($maxRef, strlen($prefix));
            $num++;
            return $prefix . str_pad((string)$num, 3, '0', STR_PAD_LEFT);
        }

        return $maxRef . '-' . uniqid();
    }

    public function saveReference(int $type, int $transNo, string $reference, ?string $description = null): void
    {
        $this->refsRepo->insert([
            'type' => (string)$type,
            'trans_no' => (string)$transNo,
            'reference' => $reference,
            'description' => $description ?? '',
        ]);
    }

    public function addComment(int $type, int $typeNo, string $date, string $memo, ?string $userEmail = null): void
    {
        $this->commentRepo->insert([
            'type' => (string)$type,
            'type_no' => (string)$typeNo,
            'date_' => $date,
            'memo' => $memo,
            'user_email' => $userEmail ?? '',
        ]);
    }

    public function findReference(int $type, int $transNo): ?string
    {
        $ref = $this->refsRepo->findByTransaction($type, $transNo);
        return $ref !== null ? $ref->getReference() : null;
    }
}
