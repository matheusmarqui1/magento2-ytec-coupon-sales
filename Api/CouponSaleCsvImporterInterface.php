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

use Ytec\CouponSales\Api\Data\CouponSaleImportResultInterface;
use Ytec\CouponSales\Model\Config\Source\ImportBehavior;

/**
 * Interface CouponSaleCsvImporterInterface
 * @package Ytec\CouponSales\Api
 * @api
 */
interface CouponSaleCsvImporterInterface
{
    /**
     * Import the CSV file.
     *
     * @param string $filePath
     * @param string $importBehavior
     * @return null|CouponSaleImportResultInterface
     * @throws \Magento\Framework\Exception\InvalidArgumentException if the file does not contain the required fields.
     */
    public function import(
        string $filePath,
        string $importBehavior = ImportBehavior::ADD
    ): ?CouponSaleImportResultInterface;
}
