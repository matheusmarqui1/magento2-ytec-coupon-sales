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
 * Class ImportBehavior
 * @package Ytec\CouponSales\Model\Config\Source
 */
class ImportBehavior implements OptionSourceInterface
{
    /**#@+
     * Import behavior constants.
     */
    public const MIXED = 'mixed';
    public const ADD = 'add';
    public const UPDATE = 'update';
    /**#@-*/

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::MIXED,
                'label' => __('Add new entries or update existing entries')
            ],
            [
                'value' => self::ADD,
                'label' => __('Add new entries only')
            ],
            [
                'value' => self::UPDATE,
                'label' => __('Update existing entries only')
            ]
        ];
    }
}
