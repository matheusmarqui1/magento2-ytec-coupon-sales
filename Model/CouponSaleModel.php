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
use Ytec\CouponSales\Model\ResourceModel\CouponSaleResource;

/**
 * Class GiftCardModel
 * @package Ytec\CouponSales\Model
 * GiftCard model.
 */
class CouponSaleModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ytec_couponsale_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(CouponSaleResource::class);
    }
}
