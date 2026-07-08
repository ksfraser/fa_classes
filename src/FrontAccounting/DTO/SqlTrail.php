<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class SqlTrail
{
    private int $id;
    private string $sql;
    private string $stamp;
    private int $userId;
    private string $errorNo;
    private string $msg;

    public function __construct(int $id, string $sql, string $stamp, int $userId, string $errorNo = '', string $msg = '')
    {
        $this->id = $id;
        $this->sql = $sql;
        $this->stamp = $stamp;
        $this->userId = $userId;
        $this->errorNo = $errorNo;
        $this->msg = $msg;
    }

    public function getId(): int { return $this->id; }
    public function getSql(): string { return $this->sql; }
    public function getStamp(): string { return $this->stamp; }
    public function getUserId(): int { return $this->userId; }
    public function getErrorNo(): string { return $this->errorNo; }
    public function getMsg(): string { return $this->msg; }
}
