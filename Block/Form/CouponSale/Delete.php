<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Block\Form\CouponSale;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;

/**
 * Class Delete
 * @package Ytec\CouponSales\Block\Form\CouponSale
 * Delete button for CouponSale form.
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Delete button settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getEntityId()) {
            return [];
        }

        return $this->wrapButtonSettings(
            __('Delete')->getText(),
            'delete',
            sprintf(
                "deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this couponsale?'),
                $this->getUrl(
                    '*/*/delete',
                    [CouponSaleInterface::ENTITY_ID => $this->getEntityId()]
                )
            ),
            [],
            20
        );
    }
}
