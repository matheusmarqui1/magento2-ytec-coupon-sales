<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api;

/**
 * Interface CouponSaleRuleManagementInterface
 * @package Ytec\CouponSales\Api
 * CouponSaleRuleManagement interface.
 */
interface CouponSaleRuleManagementInterface
{
    /**@#+
     * Constants defined for REST API messages.
     */
    public const COUPON_SALE_RULE_NOT_FOUND_MESSAGE = 'Coupon Sale Rule with ID \'%1\' not found.';
    public const RULE_IS_NOT_COUPON_SALE_RULE_MESSAGE = 'The rule with ID \'%1\' is not a Coupon Sale Rule.';
    public const COUPON_SALE_RULE_FETCH_ERROR_MESSAGE =
        'An error occurred while trying to retrieve the Coupon Sale Rule \'%1\': %2.';
    /**@#-*/

    /**
     * Get Coupon Sale Rule by ID.
     * @param int $ruleId
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function getById(int $ruleId): \Ytec\Base\Api\Rest\RestResponseInterface;

    /**
     * Get Coupon Sale Rule listing.
     * @return \Ytec\Base\Api\Rest\RestResponseInterface
     */
    public function getListing(): \Ytec\Base\Api\Rest\RestResponseInterface;
}
