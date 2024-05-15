<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model;

use Magento\Framework\Model\AbstractModel;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeResource;

/**
 * Class CouponSaleTypeModel
 * @package Ytec\CouponSales\Model
 * CouponSaleType model.
 */
class CouponSaleTypeModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ytec_couponsale_type_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(CouponSaleTypeResource::class);
    }
}
