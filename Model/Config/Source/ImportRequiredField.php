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
 * Class ImportRequiredField
 * @package Ytec\CouponSales\Model\Config\Source
 */
class ImportRequiredField implements OptionSourceInterface
{
    /**
     * Use Reflection to get all constant fields from CouponSaleInterface.
     */
    public function toOptionArray(): array
    {
        $reflection = new \ReflectionClass(\Ytec\CouponSales\Api\Data\CouponSaleInterface::class);

        return array_map(
            function (string $constant) {
                return [
                    'value' => $constant,
                    'label' => $constant
                ];
            },
            $reflection->getConstants()
        );
    }
}
