<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Data;

use Magento\SalesRule\Model\Data\Rule as MagentoRule;
use Ytec\CouponSales\Api\Data\RuleInterface;

/**
 * Class Rule
 * @package Ytec\CouponSales\Model\Data
 * Coupon Sale rule data model.
 */
class Rule extends MagentoRule implements RuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIsPartnerSalesRule(): bool
    {
        return (bool)$this->_get(self::IS_PARTNER_SALES_RULE);
    }

    /**
     * {@inheritDoc}
     */
    public function setIsPartnerSalesRule(bool $isPartnerSalesRule): RuleInterface
    {
        return $this->setData(self::IS_PARTNER_SALES_RULE, $isPartnerSalesRule);
    }

    /**
     * {@inheritDoc}
     */
    public function getCouponSaleCouponTemplate(): ?string
    {
        return $this->_get(self::COUPONSALE_COUPON_TEMPLATE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCouponSaleCouponTemplate(string $couponSaleCouponTemplate): RuleInterface
    {
        return $this->setData(self::COUPONSALE_COUPON_TEMPLATE, $couponSaleCouponTemplate);
    }

    /**
     * {@inheritDoc}
     */
    public function getCouponSaleCouponTypeCode(): ?string
    {
        return $this->_get(self::COUPONSALE_COUPON_TYPE_CODE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCouponSaleCouponTypeCode(string $couponSaleCouponTypeCode): RuleInterface
    {
        return $this->setData(self::COUPONSALE_COUPON_TYPE_CODE, $couponSaleCouponTypeCode);
    }
}
