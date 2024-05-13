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

use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterface;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Ui\Component\Listing\Column\SalesId;
use Ytec\CouponSales\Helper\ProductDiscountUsage;

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
     * @var CouponSaleOrderInterfaceFactory
     */
    private CouponSaleOrderInterfaceFactory $couponSaleOrderFactory;

    /**
     * @var SalesId
     */
    private SalesId $salesId;

    /**
     * @var ProductDiscountUsage
     */
    private ProductDiscountUsage $productDiscountUsage;

    /**
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param CouponSaleOrderInterfaceFactory $couponSaleOrderFactory
     * @param ProductDiscountUsage $productDiscountUsage
     * @param SalesId $salesId
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        OrderExtensionFactory $orderExtensionFactory,
        CouponSaleOrderInterfaceFactory $couponSaleOrderFactory,
        ProductDiscountUsage $productDiscountUsage,
        SalesId $salesId
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->couponSaleOrderFactory = $couponSaleOrderFactory;
        $this->productDiscountUsage = $productDiscountUsage;
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
                /** @var CouponSaleInterface|CouponSaleModel $couponSale */
                if ($couponSale = $this->couponSaleRepository->getCouponSaleByCode($couponCode)) {
                    $orderExtensionAttributes = $result->getExtensionAttributes();
                    if ($orderExtensionAttributes === null) {
                        $orderExtensionAttributes = $this->orderExtensionFactory->create();
                    }
                    /** @var CouponSaleOrderInterface|DataObject $couponSale */
                    $couponSale = $this->couponSaleOrderFactory->create()
                        ->addData($couponSale->getData())
                        ->setData(CouponSaleOrderInterface::SALES_ID, $this->salesId->getSalesId($couponCode))
                        ->setData(
                            CouponSaleOrderInterface::PRODUCT_DISCOUNT_USAGE,
                            $this->productDiscountUsage->execute($result, (int)$couponSale->getRuleId())
                        );
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
