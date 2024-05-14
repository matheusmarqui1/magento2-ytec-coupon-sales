<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api\Data;

use Magento\SalesRule\Api\Data\RuleInterface as MagentoRuleInterface;

/**
 * Interface RuleInterface
 * @package Ytec\CouponSales\Api\Data
 * Coupon Sale rule interface, extends Magento rule interface just for method/type hinting.
 */
interface RuleInterface extends MagentoRuleInterface
{
    /**#@+
     * Constants defined for keys of the data array
     */
    public const IS_PARTNER_SALES_RULE = 'is_partner_sales_rule';
    public const GIFTCARD_COUPON_TEMPLATE = 'couponsale_coupon_template';
    /**#@-*/

    /**
     * Get is partner sales rule
     *
     * @return bool
     */
    public function getIsPartnerSalesRule(): bool;

    /**
     * Set is partner sales rule
     *
     * @param bool $isPartnerSalesRule
     * @return $this
     */
    public function setIsPartnerSalesRule(bool $isPartnerSalesRule): self;

    /**
     * Get Coupon Sale coupon template
     *
     * @return string|null
     */
    public function getCouponSaleCouponTemplate(): ?string;

    /**
     * Set Coupon Sale coupon template
     *
     * @param string $couponSaleCouponTemplate
     * @return $this
     */
    public function setCouponSaleCouponTemplate(string $couponSaleCouponTemplate): self;
}
