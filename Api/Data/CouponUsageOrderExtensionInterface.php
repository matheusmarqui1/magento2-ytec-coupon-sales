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

/**
 * Interface CouponSaleUsageOrderExtensionInterface
 * Used to define the data model for the "coupon_usage" order extension attribute.
 * It's a synthesis of some coupon usage data and in relation with the order data.
 * @package Ytec\CouponSales\Api\Data
 * @api
 */
interface CouponUsageOrderExtensionInterface
{
    /**#@+
     * String constants for property names
     */
    public const COUPON_CODE = "coupon_code";
    public const COUPON_TYPE_CODE = "coupon_type_code";
    public const PRODUCT_DISCOUNT_USAGE = "product_discount_usage";
    public const PARTNER_COUPON_SALES = "partner_coupon_sales";
    /**#@-*/

    /**
     * Getter for CouponCode.
     *
     * @return string|null
     */
    public function getCouponCode(): ?string;

    /**
     * Getter for CouponTypeCode.
     *
     * @return string|null
     */
    public function getCouponTypeCode(): ?string;

    /**
     * Getter for ProductDiscountUsage.
     *
     * @return \Magento\Framework\DataObject[]|null
     */
    public function getProductDiscountUsage(): ?array;

    /**
     * Getter for PartnerCouponSales.
     *
     * @return \Ytec\CouponSales\Api\Data\CouponSaleOrderInterface|null
     */
    public function getPartnerCouponSales(): ?\Ytec\CouponSales\Api\Data\CouponSaleOrderInterface;
}
