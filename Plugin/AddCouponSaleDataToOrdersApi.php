<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterfaceFactory;
use Ytec\CouponSales\Helper\ProductDiscountUsage;
use Ytec\CouponSales\Helper\ExtendedCouponUsageOrderDetails;

/**
 * Class AddCouponSaleDataToOrdersApi
 * @package Ytec\CouponSales\Plugin
 */
class AddCouponSaleDataToOrdersApi
{
    /**
     * @var OrderExtensionFactory
     */
    private OrderExtensionFactory $orderExtensionFactory;

    /**
     * @var ExtendedCouponUsageOrderDetails
     */
    private ExtendedCouponUsageOrderDetails $extendedCouponUsageOrderDetails;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param ExtendedCouponUsageOrderDetails $extendedCouponUsageOrderDetails
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        ExtendedCouponUsageOrderDetails $extendedCouponUsageOrderDetails
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->extendedCouponUsageOrderDetails = $extendedCouponUsageOrderDetails;
    }

    /**
     * Add Coupon Sale data to order.
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $result): OrderInterface
    {
        if ($couponCode = $result->getCouponCode()) {
            $orderExtensionAttributes = $result->getExtensionAttributes();

            if ($orderExtensionAttributes === null) {
                $orderExtensionAttributes = $this->orderExtensionFactory->create();
            }

            $extendedCouponUsage = $this->extendedCouponUsageOrderDetails->build($result, $couponCode);

            if ($extendedCouponUsage) {
                $orderExtensionAttributes->setCouponUsage($extendedCouponUsage);
            }

            $result->setExtensionAttributes($orderExtensionAttributes);
        }

        return $result;
    }

    /**
     * Add Coupon Sale data to orders list.
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $result
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $result
    ): OrderSearchResultInterface {
        foreach ($result->getItems() as &$order) {
            $order = $this->afterGet($subject, $order);
        }

        return $result;
    }
}
