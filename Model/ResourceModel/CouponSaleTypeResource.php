<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;

/**
 * Class CouponSaleTypeResource
 * @package Ytec\CouponSales\Model\ResourceModel
 * CouponSaleType resource model.
 */
class CouponSaleTypeResource extends AbstractDb
{
    /**
     * @var string
     */
    public const TABLE = 'ytec_couponsale_type';

    /**
     * @var string
     */
    protected string $_eventPrefix = 'ytec_couponsale_type_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE, CouponSaleTypeInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
