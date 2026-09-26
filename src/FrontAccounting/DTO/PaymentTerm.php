<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PaymentTerm
{
    /** @var int */
    private $termsId;
    /** @var string */
    private $termsName;
    /** @var float */
    private $daysBeforeDue;
    /** @var float */
    private $dayInFollowingMonth;
    /** @var int */
    private $termsIndicator;
    /** @var bool */
    private $inactive;

    public function __construct(
        int $termsId,
        string $termsName,
        float $daysBeforeDue,
        float $dayInFollowingMonth,
        int $termsIndicator,
        bool $inactive = false
    ) {
        $this->termsId = $termsId;
        $this->termsName = $termsName;
        $this->daysBeforeDue = $daysBeforeDue;
        $this->dayInFollowingMonth = $dayInFollowingMonth;
        $this->termsIndicator = $termsIndicator;
        $this->inactive = $inactive;
    }

    public function getTermsId(): int { return $this->termsId; }
    public function getTermsName(): string { return $this->termsName; }
    public function getDaysBeforeDue(): float { return $this->daysBeforeDue; }
    public function getDayInFollowingMonth(): float { return $this->dayInFollowingMonth; }
    public function getTermsIndicator(): int { return $this->termsIndicator; }
    public function getInactive(): bool { return $this->inactive; }
}
