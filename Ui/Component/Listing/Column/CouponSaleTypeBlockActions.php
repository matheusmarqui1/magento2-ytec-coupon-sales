<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Ui\Component\Listing\Column;

use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;

/**
 * Class CouponSaleTypeBlockActions
 * @package Ytec\CouponSales\Ui\Component\Listing\Column
 * CouponSaleType block actions.
 */
class CouponSaleTypeBlockActions extends CouponSaleBlockActions
{
    /**
     * Entity name.
     */
    protected const ENTITY_NAME = 'Coupon Type';

    /**
     * Entity id field.
     */
    protected const ENTITY_ID = CouponSaleTypeInterface::ENTITY_ID;

    /**
     * Url paths.
     */
    protected const EDIT_URL_PATH = 'ytec_couponsales_type/couponsaletype/edit';
    protected const DELETE_URL_PATH = 'ytec_couponsales_type/couponsaletype/delete';
}
