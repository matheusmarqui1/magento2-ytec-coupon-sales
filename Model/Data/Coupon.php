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

use Ytec\CouponSales\Api\Data\CouponInterface;
use Magento\SalesRule\Model\Coupon as MagentoCoupon;

/**
 * Class Coupon
 * @package Ytec\CouponSales\Model\Data
 */
class Coupon extends MagentoCoupon implements CouponInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCustomCouponType(): ?string
    {
        return $this->getData(self::CUSTOM_COUPON_TYPE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomCouponType(string $customCouponType): CouponInterface
    {
        $this->setData(self::CUSTOM_COUPON_TYPE, $customCouponType);
        return $this;
    }
}

