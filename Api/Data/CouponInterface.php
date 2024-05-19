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

use Magento\SalesRule\Api\Data\CouponInterface as MagentoCouponInterface;

/**
 * Interface CouponInterface
 * @package Ytec\CouponSales\Api\Data
 * Coupon interface.
 */
interface CouponInterface extends MagentoCouponInterface
{
    /**#@+
     * Constants defined for keys of the data array
     */
    public const CUSTOM_COUPON_TYPE = 'custom_coupon_type';
    /**#@-*/

    /**
     * Get custom coupon type.
     *
     * @return string|null
     */
    public function getCustomCouponType(): ?string;

    /**
     * Set custom coupon type.
     *
     * @param string $customCouponType
     * @return $this
     */
    public function setCustomCouponType(string $customCouponType): self;
}
