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
    public const XML_PATH_MAKE_AVAILABLE_ON_CANCELLATION
        = 'ytec_couponsales/general/make_coupon_sale_available_on_cancel';
    public const XML_PATH_MAKE_AVAILABLE_ON_REFUND
        = 'ytec_couponsales/general/make_coupon_sale_available_on_refund';
    public const XML_PATH_SALES_ID_REGEX = 'ytec_couponsales/general/sales_id_regex';
    public const XML_PATH_SALES_ID_REGEX_EXTRACTION_ERROR_BEHAVIOR =
        'ytec_couponsales/general/sales_id_extraction_error_behavior';
    /**#@-*/

    /**#@+
     * Coupon Sale import configuration keys.
     */
    public const XML_PATH_IMPORT_REQUIRED_FIELDS = 'ytec_couponsales/import/required_fields';
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
    public const XML_PATH_ENDPOINTS_BULK_DISABLE_VOUCHER_ENABLE = 'ytec_couponsales/endpoints/bulk_disable_voucher/enable';
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
     * Check if we should make coupon sale available on cancellation.
     *
     * @return bool
     */
    public function shouldMakeAvailableOnCancellation(): bool;

    /**
     * Check if we should make coupon sale available on refund.
     *
     * @return bool
     */
    public function shouldMakeAvailableOnRefund(): bool;

    /**
     * Get the sales ID regex.
     *
     * @return string
     */
    public function getSalesIdRegex(): string;

    /**
     * Get the sales ID extraction error behavior.
     *
     * @return string
     */
    public function getSalesIdExtractionErrorBehavior(): string;

    /**
     * Get the required fields for import.
     *
     * @return array
     */
    public function getImportRequiredFields(): array;

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

    /**
     * Check if bulk disable voucher endpoint is enabled.
     *
     * @return bool
     */
    public function isBulkDisableVoucherEndpointEnabled(): bool;
}
