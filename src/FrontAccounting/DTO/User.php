<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class User
{
    /** @var int */
    private $id;
    /** @var string */
    private $userId;
    /** @var ?string */
    private $realName;
    /** @var string */
    private $email;
    /** @var ?string */
    private $phone;
    /** @var ?int */
    private $language;
    /** @var ?string */
    private $dateFormat;
    /** @var bool */
    private $showHints;
    /** @var bool */
    private $showGraphic;
    /** @var ?string */
    private $querySize;
    /** @var bool */
    private $showCurrency;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $id,
        string $userId,
        ?string $realName = null,
        string $email = '',
        ?string $phone = null,
        ?int $language = null,
        ?string $dateFormat = null,
        bool $showHints = true,
        bool $showGraphic = true,
        ?string $querySize = null,
        bool $showCurrency = false,
        bool $inactive = false
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->realName = $realName;
        $this->email = $email;
        $this->phone = $phone;
        $this->language = $language;
        $this->dateFormat = $dateFormat;
        $this->showHints = $showHints;
        $this->showGraphic = $showGraphic;
        $this->querySize = $querySize;
        $this->showCurrency = $showCurrency;
        $this->inactive = $inactive;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): string { return $this->userId; }
    public function getRealName(): ?string { return $this->realName; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getLanguage(): ?int { return $this->language; }
    public function getDateFormat(): ?string { return $this->dateFormat; }
    public function getShowHints(): bool { return $this->showHints; }
    public function getShowGraphic(): bool { return $this->showGraphic; }
    public function getQuerySize(): ?string { return $this->querySize; }
    public function getShowCurrency(): bool { return $this->showCurrency; }
    public function getInactive(): bool { return $this->inactive; }
}
