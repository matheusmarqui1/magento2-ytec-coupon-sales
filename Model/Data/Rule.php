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
    public function getIsPartnerSalesRule(): bool
    {
        return (bool)$this->_get(self::IS_PARTNER_SALES_RULE);
    }

    public function setIsPartnerSalesRule(bool $isPartnerSalesRule): RuleInterface
    {
        return $this->setData(self::IS_PARTNER_SALES_RULE, $isPartnerSalesRule);
    }

    public function getCouponSaleCouponTemplate(): ?string
    {
        return $this->_get(self::GIFTCARD_COUPON_TEMPLATE);
    }

    public function setCouponSaleCouponTemplate(string $couponSaleCouponTemplate): RuleInterface
    {
        return $this->setData(self::GIFTCARD_COUPON_TEMPLATE, $couponSaleCouponTemplate);
    }
}
