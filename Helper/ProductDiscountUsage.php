<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Helper;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\DataObjectFactory;

/**
 * Class ProductDiscountUsage
 * @package Ytec\CouponSales\Helper
 */
class ProductDiscountUsage
{
    /**
     * @var DataObjectFactory
     */
    private DataObjectFactory $dataObjectFactory;

    /**
     * ProductDiscountUsage constructor.
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(DataObjectFactory $dataObjectFactory)
    {
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * Get the products that have been discounted by a specific coupon sale rule.
     *
     * @param OrderInterface $order
     * @param int $ruleId
     * @return DataObject[]
     */
    public function execute(OrderInterface $order, int $ruleId): array
    {
        $discountedProducts = [];

        foreach ($order->getItems() as $item) {
            if ($item->getAppliedRuleIds() && in_array($ruleId, explode(',', $item->getAppliedRuleIds()))) {
                $discountedProducts[] = $this->dataObjectFactory->create([
                    'data' => [
                        CartItemInterface::KEY_SKU => $item->getSku(),
                        CartItemInterface::KEY_NAME => $item->getName(),
                        CartItemInterface::KEY_ITEM_ID => $item->getItemId()
                    ]
                ]);
            }
        }

        return $discountedProducts;
    }
}
