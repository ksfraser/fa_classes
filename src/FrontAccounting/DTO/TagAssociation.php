<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class TagAssociation
{
    /** @var int */
    private $id;
    /** @var int */
    private $tagId;
    /** @var int */
    private $transType;
    /** @var int */
    private $transNo;

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
