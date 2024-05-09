<?php

namespace Ytec\CouponSales\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;

class CouponSaleResource extends AbstractDb
{
    /**
     * @var string
     */
    public const MAIN_TABLE = 'ytec_couponsale';

    /**
     * @var string
     */
    protected string $_eventPrefix = 'ytec_couponsale_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, CouponSaleInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
