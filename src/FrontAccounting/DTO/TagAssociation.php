<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TagAssociation
{
    private int $id;
    private int $tagId;
    private int $transType;
    private int $transNo;

    public function __construct(int $id, int $tagId, int $transType, int $transNo)
    {
        $this->id = $id;
        $this->tagId = $tagId;
        $this->transType = $transType;
        $this->transNo = $transNo;
    }

    public function getId(): int { return $this->id; }
    public function getTagId(): int { return $this->tagId; }
    public function getTransType(): int { return $this->transType; }
    public function getTransNo(): int { return $this->transNo; }
}
