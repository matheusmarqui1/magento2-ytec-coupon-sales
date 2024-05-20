<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class SalesIdExtractionErrorBehavior
 * @package Ytec\CouponSales\Model\Config\Source
 */
class SalesIdExtractionErrorBehavior implements OptionSourceInterface
{
    /**#@+
     * Sales ID extraction error behavior
     */
    public const LEAVE_EMPTY = 'leave_empty';
    public const THROW_ERROR = 'throw_error';
    /**#@-*/

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::LEAVE_EMPTY,
                'label' => __('Leave empty and allow saving the coupon sale.')
            ],
            [
                'value' => self::THROW_ERROR,
                'label' => __('Throw error and prevent saving the coupon sale.')
            ]
        ];
    }
}
