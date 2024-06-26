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

/**
 * Interface CouponSaleManagementInterface
 * @package Ytec\CouponSales\Api
 * @api
 */
interface CouponSaleManagementInterface
{
    /**#@+
     * Constants defined for response body messages.
     */
    public const COUPON_SALE_NOT_FOUND_MESSAGE = 'Coupon Sale with code \'%1\' not found.';
    public const COUPON_SALE_FETCH_ERROR_MESSAGE = 'An error occurred while trying to retrieve the Coupon Sale \'%1\': %2.';
    public const COUPON_SALE_DELETE_ERROR_MESSAGE = 'An error occurred while trying to delete the Coupon Sale \'%1\': %2';
    public const COUPON_SALE_SAVE_ERROR_MESSAGE = 'An error occurred while trying to save the Coupon Sale \'%1\': %2';
    public const RULE_NOT_FOUND_MESSAGE = 'The rule \'%1\' related to the Coupon Sale \'%2\' does not exist.';
    public const COUPON_SALES_SUCCESS_MESSAGE = 'Coupon Sale(s) created successfully.';
    public const MODULE_DISABLED_MESSAGE = 'The partner Coupon Sale module or current endpoint is currently disabled.';
    public const COUPON_SALE_ALREADY_USED_MESSAGE = 'The Coupon Sale with code \'%1\' has already been used.';
    public const BULK_DISABLE_COUPON_SALES_ERROR_MESSAGE = 'Some errors occurred while disabling the coupon sales in bulk.';
    /**#@-*/

    /**
     * Get Coupon Sale by code.
     * @param string $code
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function getByCode(string $code): \Ytec\Base\Api\Rest\RestResponseInterface;

    /**
     * Delete Coupon Sale by code.
     * @param string $code
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function deleteByCode(string $code): \Ytec\Base\Api\Rest\RestResponseInterface;

    /**
     * Disable Coupon Sale by code.
     * @param string $code
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function disableByCode(string $code): \Ytec\Base\Api\Rest\RestResponseInterface;

    /**
     * Bulk disable Coupon Sales by code.
     * @param string[] $codes
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function disableByCodeInBulk(array $codes): \Ytec\Base\Api\Rest\RestResponseInterface;

    /**
     * Create Coupon Sales.
     * @param \Ytec\CouponSales\Api\Data\CouponSaleInterface[] $couponSales
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function createCouponSales(array $couponSales): \Ytec\Base\Api\Rest\RestResponseInterface;
}
