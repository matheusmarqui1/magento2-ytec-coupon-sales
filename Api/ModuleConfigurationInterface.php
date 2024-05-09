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
 * Interface ModuleConfigurationInterface
 * @package Ytec\CouponSales\Api
 * @api
 */
interface ModuleConfigurationInterface
{
    /**#@+
     * General configuration keys.
     */
    public const XML_PATH_ENABLED = 'ytec_couponsales/general/enable';
    public const XML_PATH_VALIDATE_CODE_TEMPLATE = 'ytec_couponsales/general/validate_templates';
    public const XML_PATH_SALES_ID_REGEX = 'ytec_couponsales/general/sales_id_regex';
    /**#@-*/

    /**#@+
     * Coupon Sale endpoints configuration keys.
     */
    public const XML_PATH_ENDPOINTS_CREATE_VOUCHER_ENABLE = 'ytec_couponsales/endpoints/create_voucher/enable';
    public const XML_PATH_ENDPOINTS_GET_VOUCHER_ENABLE = 'ytec_couponsales/endpoints/get_voucher/enable';
    public const XML_PATH_ENDPOINTS_DELETE_VOUCHER_ENABLE = 'ytec_couponsales/endpoints/delete_voucher/enable';
    public const XML_PATH_ENDPOINTS_DELETE_VOUCHER_IS_SOFT_DELETE =
        'ytec_couponsales/endpoints/delete_voucher/soft_delete';
    public const XML_PATH_ENDPOINTS_DISABLE_VOUCHER_ENABLE = 'ytec_couponsales/endpoints/disable_voucher/enable';
    /**#@-*/

    /**
     * Check if module is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Check if code template validation is enabled.
     *
     * @return bool
     */
    public function isCodeTemplateValidationEnabled(): bool;

    /**
     * Get the sales ID regex.
     *
     * @return string
     */
    public function getSalesIdRegex(): string;

    /**
     * Check if create voucher endpoint is enabled.
     *
     * @return bool
     */
    public function isCreateVoucherEndpointEnabled(): bool;

    /**
     * Check if get voucher endpoint is enabled.
     *
     * @return bool
     */
    public function isGetVoucherEndpointEnabled(): bool;

    /**
     * Check if delete voucher endpoint is enabled.
     *
     * @return bool
     */
    public function isDeleteVoucherEndpointEnabled(): bool;

    /**
     * Check if delete voucher is soft delete.
     *
     * @return bool
     */
    public function isDeleteVoucherSoftDelete(): bool;

    /**
     * Check if disable voucher endpoint is enabled.
     *
     * @return bool
     */
    public function isDisableVoucherEndpointEnabled(): bool;
}
