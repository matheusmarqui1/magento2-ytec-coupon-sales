<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Ui\Component\Listing\Column\SalesId;

class AddCouponSaleDataToOrdersApi
{
    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var OrderExtensionFactory
     */
    private OrderExtensionFactory $orderExtensionFactory;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleFactory;

    /**
     * @var SalesId
     */
    private SalesId $salesId;

    /**
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param SalesId $salesId
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        OrderExtensionFactory $orderExtensionFactory,
        CouponSaleInterfaceFactory $couponSaleFactory,
        SalesId $salesId
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->salesId = $salesId;
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
            try {
                if ($couponSale = $this->couponSaleRepository->getCouponSaleByCode($couponCode)) {
                    $orderExtensionAttributes = $result->getExtensionAttributes();
                    if ($orderExtensionAttributes === null) {
                        $orderExtensionAttributes = $this->orderExtensionFactory->create();
                    }
                    /** @var CouponSaleInterface $couponSale */
                    $couponSale = $this->couponSaleFactory->create()
                        ->addData($couponSale->getData())
                        ->setSalesId($this->salesId->getSalesId($couponCode));
                    $orderExtensionAttributes->setCouponSale($couponSale);
                    $result->setExtensionAttributes($orderExtensionAttributes);
                }
            } catch (NoSuchEntityException $exception) {
                /** Not a couponsale order. */
                return $result;
            }
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
        $orders = [];

        foreach ($result->getItems() as $order) {
            $orders[] = $this->afterGet($subject, $order);
        }

        $result->setItems($orders);

        return $result;
    }
}
