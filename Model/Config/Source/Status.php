<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    public const AVAILABLE = 'available';
    public const USED = 'used';
    public const EXPIRED = 'expired';
    public const DISABLED = 'disabled';
    public const DISABLED_BY_PARTNER = 'disabled_by_partner';

    /**
     * Get status options for Coupon Sales.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => static::AVAILABLE, 'label' => __('Available')],
            ['value' => static::USED, 'label' => __('Used')],
            ['value' => static::EXPIRED, 'label' => __('Expired')],
            ['value' => static::DISABLED, 'label' => __('Disabled')],
            ['value' => static::DISABLED_BY_PARTNER, 'label' => __('Disabled by partner')],
        ];
    }
}
