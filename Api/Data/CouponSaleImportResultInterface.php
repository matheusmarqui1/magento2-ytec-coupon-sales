<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Api\Data;

/**
 * Interface CouponSaleImportResultInterface
 * @package Ytec\CouponSales\Api\Data
 * @api
 */
interface CouponSaleImportResultInterface
{
    /**#@+
     * String constants for property names
     */
    public const TOTAL_ROWS_QUANTITY = "total_rows_quantity";
    public const IMPORTED_ROWS_QUANTITY = "imported_rows_quantity";
    public const ERROR_ROWS_QUANTITY = "error_rows_quantity";
    public const ERRORS = "errors";
    public const IS_COMPLETE_SUCCESS = "is_complete_success";
    /**#@-*/

    /**
     * Getter for TotalRowsQuantity.
     *
     * @return int
     */
    public function getTotalRowsQuantity(): int;

    /**
     * Setter for TotalRowsQuantity.
     *
     * @param int $totalRowsQuantity
     *
     * @return CouponSaleImportResultInterface
     */
    public function setTotalRowsQuantity(int $totalRowsQuantity): CouponSaleImportResultInterface;

    /**
     * Getter for ImportedRowsQuantity.
     *
     * @return int
     */
    public function getImportedRowsQuantity(): int;

    /**
     * Increment ImportedRowsQuantity.
     *
     * @return CouponSaleImportResultInterface
     */
    public function incrementImportedRowsQuantity(): CouponSaleImportResultInterface;

    /**
     * Setter for ImportedRowsQuantity.
     *
     * @param int $importedRowsQuantity
     *
     * @return CouponSaleImportResultInterface
     */
    public function setImportedRowsQuantity(int $importedRowsQuantity): CouponSaleImportResultInterface;

    /**
     * Getter for ErrorRowsQuantity.
     *
     * @return int
     */
    public function getErrorRowsQuantity(): int;

    /**
     * Increment ErrorRowsQuantity.
     *
     * @return CouponSaleImportResultInterface
     */
    public function incrementErrorRowsQuantity(): CouponSaleImportResultInterface;

    /**
     * Setter for ErrorRowsQuantity.
     *
     * @param int $errorRowsQuantity
     *
     * @return CouponSaleImportResultInterface
     */
    public function setErrorRowsQuantity(int $errorRowsQuantity): CouponSaleImportResultInterface;

    /**
     * Getter for Errors.
     *
     * @return array with errors:
     * [
     *    [
     *       'row_number' => 1,
     *       'coupon_sale_code' => 'XXXX_9999',
     *       'error_message' => 'Error message',
     *    ],
     * ]
     */
    public function getErrors(): array;

    /**
     * Setter for Errors.
     *
     * @param array $errors
     *
     * @return CouponSaleImportResultInterface
     */
    public function setErrors(array $errors): CouponSaleImportResultInterface;

    /**
     * Add error to the list.
     *
     * @param int $rowNumber
     * @param string $couponSaleCode
     * @param string $errorMessage
     *
     * @return CouponSaleImportResultInterface
     */
    public function addError(int $rowNumber, string $couponSaleCode, string $errorMessage): CouponSaleImportResultInterface;

    /**
     * Getter for IsCompleteSuccess.
     *
     * @return bool
     */
    public function getIsCompleteSuccess(): bool;
}
