<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Model\CouponSaleModel;

/**
 * Class CouponSaleDataMapper
 * @package Ytec\CouponSales\Mapper
 * CouponSale data mapper.
 */
class CouponSaleDataMapper
{
    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $entityDtoFactory;

    /**
     * @param CouponSaleInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        CouponSaleInterfaceFactory $entityDtoFactory
    ) {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|CouponSaleInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var CouponSaleModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var CouponSaleInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
