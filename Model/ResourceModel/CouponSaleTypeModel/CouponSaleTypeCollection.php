<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ytec\CouponSales\Model\CouponSaleTypeModel;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeResource;

/**
 * Class CouponSaleTypeCollection
 * @package Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeModel
 * CouponSaleType collection model.
 */
class CouponSaleTypeCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ytec_couponsale_type_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct(): void
    {
        $this->_init(CouponSaleTypeModel::class, CouponSaleTypeResource::class);
    }
}
