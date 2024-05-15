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
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterfaceFactory;
use Ytec\CouponSales\Model\CouponSaleTypeModel;

/**
 * Class CouponSaleTypeDataMapper
 * @package Ytec\CouponSales\Mapper
 * CouponSaleType data mapper.
 */
class CouponSaleTypeDataMapper
{
    /**
     * @var CouponSaleTypeInterfaceFactory
     */
    private CouponSaleTypeInterfaceFactory $entityDtoFactory;

    /**
     * @param CouponSaleTypeInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        CouponSaleTypeInterfaceFactory $entityDtoFactory
    ) {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|CouponSaleTypeInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var CouponSaleTypeModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var CouponSaleTypeInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
