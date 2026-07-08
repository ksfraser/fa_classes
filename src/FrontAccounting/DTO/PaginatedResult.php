<?php

declare(strict_types=1);

namespace FrontAccounting\DTO;

final class PaginatedResult
{
    private array $items;
    private int $total;
    private int $page;
    private int $perPage;
    private int $totalPages;

    public function __construct(array $items, int $total, int $page, int $perPage)
    {
        $this->items = $items;
        $this->total = $total;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 0;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function hasPrevious(): bool
    {
        return $this->page > 1;
    }

    public function getFrom(): int
    {
        return ($this->page - 1) * $this->perPage + 1;
    }

    public function getTo(): int
    {
        return min($this->page * $this->perPage, $this->total);
    }
}
