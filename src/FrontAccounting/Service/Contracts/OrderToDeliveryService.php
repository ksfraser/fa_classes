<?php

declare(strict_types=1);

namespace FrontAccounting\Service\Contracts;

/**
 * @since 2026-07-10
 * Service contract for purchase-order-to-delivery delay analysis.
 *
 * Replaces the legacy fa_order_to_delivery class which depended on the
 * external table_interface base class and contained several bugs
 * (mismatched method names, malformed WHERE clauses).
 *
 * ┌────────────────────────────────────────────────────────────┐
 * │                   OrderToDeliveryService                    │
 * ├────────────────────────────────────────────────────────────┤
 * │  + getItemDelays(?string $itemCode): array                 │
 * │  + getSupplierDelays(?string $supplierName): array         │
 * │  + getOrderDeliveryDetails(?int $orderNo): array           │
 * └────────────────────────────────────────────────────────────┘
 */
interface OrderToDeliveryService
{
    /**
     * Return delivery delay (days) aggregated by item code and supplier.
     *
     * @param string|null $itemCode  Filter to a single item code.
     * @return array<int, array{stock_id: string, supplier: string, days: int}>
     */
    public function getItemDelays(?string $itemCode = null): array;

    /**
     * Return delivery delay (days) aggregated by purchase order and supplier.
     *
     * @param string|null $supplierName  Filter to a single supplier name.
     * @return array<int, array{order_number: int, supplier: string, days: int}>
     */
    public function getSupplierDelays(?string $supplierName = null): array;

    /**
     * Return detailed delivery info per purchase-order line.
     *
     * @param int|null $orderNo  Filter to a single order number.
     * @return array<int, array{
     *     order_number: int,
     *     supplier: string,
     *     days: int,
     *     order_date: string,
     *     delivery_date: string,
     *     stock_id: string,
     *     quantity_ordered: float,
     *     quantity_received: float
     * }>
     */
    public function getOrderDeliveryDetails(?int $orderNo = null): array;
}
