<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PrintProfile
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $reportId;
    /** @var string */
    private $printerName;
    /** @var bool */
    private $inactive;

    public function __construct(int $id, string $name, int $reportId, string $printerName = '', bool $inactive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reportId = $reportId;
        $this->printerName = $printerName;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getReportId(): int { return $this->reportId; }
    public function getPrinterName(): string { return $this->printerName; }
    public function getInactive(): bool { return $this->inactive; }
}
