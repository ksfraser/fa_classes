<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class Attachment
{
    /** @var int */
    private $id;
    /** @var int */
    private $type;
    /** @var int */
    private $typeNo;
    /** @var string */
    private $filename;
    /** @var ?string */
    private $fileType;
    /** @var int */
    private $fileSize;
    /** @var string */
    private $content;
    /** @var ?string */
    private $description;
    /** @var ?string */
    private $date_;

    public function __construct(
        int $id,
        int $type,
        int $typeNo,
        string $filename,
        ?string $fileType,
        int $fileSize,
        string $content,
        ?string $description = null,
        ?string $date_ = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->typeNo = $typeNo;
        $this->filename = $filename;
        $this->fileType = $fileType;
        $this->fileSize = $fileSize;
        $this->content = $content;
        $this->description = $description;
        $this->date_ = $date_;
    }

    public function getId(): int { return $this->id; }
    public function getType(): int { return $this->type; }
    public function getTypeNo(): int { return $this->typeNo; }
    public function getFilename(): string { return $this->filename; }
    public function getFileType(): ?string { return $this->fileType; }
    public function getFileSize(): int { return $this->fileSize; }
    public function getContent(): string { return $this->content; }
    public function getDescription(): ?string { return $this->description; }
    public function getDate(): ?string { return $this->date_; }
}
