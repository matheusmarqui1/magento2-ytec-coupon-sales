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

use Ytec\CouponSales\Api\Data\CouponSaleImportResultInterface;
use Magento\Framework\DataObject;

/**
 * Class CouponSaleImportResult
 * @package Ytec\CouponSales\Model\Data
 */
class CouponSaleImportResult extends DataObject implements CouponSaleImportResultInterface
{
    /**
     * @inheritDoc
     */
    public function getTotalRowsQuantity(): int
    {
        return $this->getData(self::TOTAL_ROWS_QUANTITY) ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function setTotalRowsQuantity(int $totalRowsQuantity): CouponSaleImportResultInterface
    {
        return $this->setData(self::TOTAL_ROWS_QUANTITY, $totalRowsQuantity);
    }

    /**
     * @inheritDoc
     */
    public function getImportedRowsQuantity(): int
    {
        return $this->getData(self::IMPORTED_ROWS_QUANTITY) ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function setImportedRowsQuantity(int $importedRowsQuantity): CouponSaleImportResultInterface
    {
        return $this->setData(self::IMPORTED_ROWS_QUANTITY, $importedRowsQuantity);
    }

    /**
     * @inheritDoc
     */
    public function incrementImportedRowsQuantity(): CouponSaleImportResultInterface
    {
        return $this->setImportedRowsQuantity($this->getImportedRowsQuantity() + 1);
    }

    /**
     * @inheritDoc
     */
    public function getErrorRowsQuantity(): int
    {
        return $this->getData(self::ERROR_ROWS_QUANTITY) ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function incrementErrorRowsQuantity(): CouponSaleImportResultInterface
    {
        return $this->setErrorRowsQuantity($this->getErrorRowsQuantity() + 1);
    }

    /**
     * @inheritDoc
     */
    public function setErrorRowsQuantity(int $errorRowsQuantity): CouponSaleImportResultInterface
    {
        return $this->setData(self::ERROR_ROWS_QUANTITY, $errorRowsQuantity);
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->getData(self::ERRORS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setErrors(array $errors): CouponSaleImportResultInterface
    {
        return $this->setData(self::ERRORS, $errors);
    }

    /**
     * @inheritDoc
     */
    public function addError(int $rowNumber, string $couponSaleCode, string $errorMessage): CouponSaleImportResultInterface
    {
        $errors = $this->getErrors();

        $errors[] = [
            'row_number' => $rowNumber,
            'coupon_sale_code' => $couponSaleCode,
            'error_message' => $errorMessage,
        ];

        $this->incrementErrorRowsQuantity();
        return $this->setErrors($errors);
    }

    /**
     * @inheritDoc
     */
    public function getIsCompleteSuccess(): bool
    {
        return $this->getErrorRowsQuantity() === 0 &&
            $this->getTotalRowsQuantity() === $this->getImportedRowsQuantity();
    }
}
