<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api\Data;

use Magento\Framework\DataObject;

/**
 * Interface CouponSaleOrderInterface
 * Used to define the data model for the "coupon_sale" order extension attribute.
 * @package Ytec\CouponSales\Api\Data
 * @api
 */
interface CouponSaleOrderInterface
{
    /**#@+
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const CODE_ID = "code_id";
    public const SALES_ID = "sales_id";
    public const PARTNER_SALES_PRICE = "partner_sales_price";
    public const STATUS = "status";
    public const PARTNER_NAME = "partner_name";
    public const HISTORY = "history";
    /**#@-*/

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Getter for CodeId.
     *
     * @return int|null
     */
    public function getCodeId(): ?int;

    /**
     * Getter for PartnerSalesPrice.
     *
     * @return float|null
     */
    public function getPartnerSalesPrice(): ?float;

    /**
     * Getter for Status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Getter for PartnerName.
     *
     * @return string|null
     */
    public function getPartnerName(): ?string;

    /**
     * Getter for History.
     *
     * @return array|null
     */
    public function getHistory(): ?array;

    /**
     * Getter for SalesId.
     *
     * @return string|null
     */
    public function getSalesId(): ?string;
}
