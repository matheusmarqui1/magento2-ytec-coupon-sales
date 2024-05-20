<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Helper;

use Magento\Framework\Exception\NoSuchEntityException;
use Ytec\CouponSales\Api\Exception\SalesIdExtractionNoMatchException;
use Ytec\CouponSales\Model\Config\ModuleConfiguration;
use Ytec\CouponSales\Model\Config\Source\SalesIdExtractionErrorBehavior;

/**
 * Class SalesId
 * @package Ytec\CouponSales\Ui\Component\Listing\Column
 * SalesId column for CouponSales listing.
 */
class SalesId
{
    /**
     * @var ModuleConfiguration
     */
    private ModuleConfiguration $moduleConfiguration;

    public function __construct(
        ModuleConfiguration $moduleConfiguration
    ) {
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * Get sales id from coupon code.
     *
     * @param string $code
     * @return string|null
     * @throws NoSuchEntityException
     * @throws SalesIdExtractionNoMatchException If the sales ID could not be extracted from the code.
     */
    public function get(string $code): ?string
    {
        try {
            $pattern = $this->moduleConfiguration->getSalesIdRegex();
        } catch (NoSuchEntityException $exception) {
            /**
             * Default pattern for sales id.
             * It will match the last 6 digits of the code.
             */
            $pattern = '/\d{6}(?=\D*$)/';
        }

        $matches = [];

        preg_match($pattern, $code, $matches);

        if (empty($matches)) {
            $salesIdExtractionErrorBehavior = $this->moduleConfiguration->getSalesIdExtractionErrorBehavior();

            switch ($salesIdExtractionErrorBehavior) {
                case SalesIdExtractionErrorBehavior::THROW_ERROR:
                    throw SalesIdExtractionNoMatchException::doesNotMatch($code, $pattern);
                case SalesIdExtractionErrorBehavior::LEAVE_EMPTY:
                    default:
                        return null;
            }
        }

        return $matches[0];
    }
}
