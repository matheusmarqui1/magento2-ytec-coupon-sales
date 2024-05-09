<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\ResourceModel\CouponSaleModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleResource;

/**
 * Class GiftCardCollection
 * @package Ytec\CouponSales\Model\ResourceModel\GiftCardModel
 * GiftCard collection model.
 */
class CouponSaleCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ytec_couponsale_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct(): void
    {
        $this->_init(CouponSaleModel::class, CouponSaleResource::class);
    }
}
