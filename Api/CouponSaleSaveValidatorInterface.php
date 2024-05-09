<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Interface CouponSaleSaveValidatorInterface
 * @package Ytec\CouponSales\Api
 * @api
 */
interface CouponSaleSaveValidatorInterface
{
    /**
     * Validate Coupon Sale data.
     *
     * @param CouponSaleInterface $couponSale
     * @return void
     * @throws NoSuchEntityException If Coupon Sale rule does not exist.
     * @throws LocalizedException If validation fails.
     */
    public function validate(CouponSaleInterface $couponSale): void;
}
