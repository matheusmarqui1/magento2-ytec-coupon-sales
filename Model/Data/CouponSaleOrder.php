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

use Magento\Framework\DataObject;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterface;

/**
 * Class CouponSaleOrder
 * @package Ytec\CouponSales\Model\Data
 * Data model for the coupon sale order extension attribute.
 */
class CouponSaleOrder extends DataObject implements CouponSaleOrderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEntityId(): ?int
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodeId(): ?int
    {
        return (int)$this->getData(self::CODE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getCode(): ?string
    {
        return (string)$this->getData(self::CODE);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerSalesPrice(): ?float
    {
        return (float)$this->getData(self::PARTNER_SALES_PRICE);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?string
    {
        return (string)$this->getData(self::STATUS);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerName(): ?string
    {
        return (string)$this->getData(self::PARTNER_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistory(): ?array
    {
        $history = $this->getData(self::HISTORY);

        $lines = explode("\n", $history);

        $items = array_map(function ($line) {
            $line = trim($line);

            if (mb_substr($line, 0, 2) === "» ") {
                return trim(mb_substr($line, 2));
            }
            return $line;
        }, $lines);

        $items = array_filter($items, fn($item) => !empty($item));

        return array_values($items);
    }

    /**
     * {@inheritDoc}
     */
    public function getSalesId(): ?string
    {
        return (string)$this->getData(self::SALES_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductDiscountUsage(): ?array
    {
        $productDiscountUsage = $this->getData(self::PRODUCT_DISCOUNT_USAGE);

        return array_map(fn(DataObject $item) => $item->getData(), $productDiscountUsage);
    }

    /**
     * {@inheritDoc}
     */
    public function getCouponTypeCode(): ?string
    {
        return (string)$this->getData(self::COUPON_TYPE_CODE);
    }
}
