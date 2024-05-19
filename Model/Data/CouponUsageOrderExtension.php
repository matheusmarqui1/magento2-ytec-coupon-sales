<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Data;

use Magento\Framework\DataObject;
use Ytec\CouponSales\Api\Data\CouponUsageOrderExtensionInterface;

/**
 * Class CouponUsageOrderExtension
 * @package Ytec\CouponSales\Model\Data
 */
class CouponUsageOrderExtension extends DataObject implements CouponUsageOrderExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function getCouponCode(): ?string
    {
        return (string)$this->getData(self::COUPON_CODE);
    }

    /**
     * @inheritDoc
     */
    public function getCouponTypeCode(): ?string
    {
        return (string)$this->getData(self::COUPON_TYPE_CODE);
    }

    /**
     * @inheritDoc
     */
    public function getProductDiscountUsage(): ?array
    {
        $productDiscountUsage = $this->getData(self::PRODUCT_DISCOUNT_USAGE);

        return array_map(fn(DataObject $item) => $item->getData(), $productDiscountUsage ?? []);
    }

    /**
     * @inheritDoc
     */
    public function getPartnerCouponSales(): ?\Ytec\CouponSales\Api\Data\CouponSaleOrderInterface
    {
        return $this->getData(self::PARTNER_COUPON_SALES);
    }
}
