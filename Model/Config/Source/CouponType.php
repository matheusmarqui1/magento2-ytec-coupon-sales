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
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Query\CouponSaleType\GetListQuery as CouponSaleTypeGetListQuery;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class CouponType
 * @package Ytec\CouponSales\Model\Config\Source
 */
class CouponType implements OptionSourceInterface
{
    private CouponSaleTypeGetListQuery $couponSaleTypeGetListQuery;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        CouponSaleTypeGetListQuery $couponSaleTypeGetListQuery,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->couponSaleTypeGetListQuery = $couponSaleTypeGetListQuery;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $couponSaleTypes = $this->couponSaleTypeGetListQuery->execute();

        return array_map(fn (CouponSaleTypeInterface $couponSaleType) => [
            'value' => $couponSaleType->getCode(),
            'label' => $couponSaleType->getIsActive() ?
                $couponSaleType->getLabel() : sprintf('%s (Inactive)', $couponSaleType->getLabel())
        ], $couponSaleTypes->getItems());
    }
}
