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

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterfaceFactory;
use Ytec\CouponSales\Api\Data\CouponSaleOrderInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Ytec\CouponSales\Model\Data\CouponUsageOrderExtensionFactory;
use Ytec\CouponSales\Api\Data\CouponUsageOrderExtensionInterface;
use Ytec\CouponSales\Helper\ProductDiscountUsage;

/**
 * Class ExtendedCouponUsageOrderDetails
 * It's a helper class to build "coupon_usage" extension attribute for order details.
 * @package Ytec\CouponSales\Helper
 */
class ExtendedCouponUsageOrderDetails
{
    /**
     * Coupon code not found message.
     */
    public const COUPON_CODE_NOT_FOUND_MESSAGE = 'Coupon code %1 not found.';

    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;

    /**
     * @var CouponSaleOrderInterfaceFactory
     */
    private CouponSaleOrderInterfaceFactory $couponSaleOrderFactory;

    /**
     * @var RuleRepositoryInterface
     */
    private RuleRepositoryInterface $ruleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var CouponUsageOrderExtensionFactory
     */
    private CouponUsageOrderExtensionFactory $couponUsageOrderExtensionFactory;

    /**
     * @var ProductDiscountUsage
     */
    private ProductDiscountUsage $productDiscountUsage;

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @param CouponRepositoryInterface $couponRepository
     * @param CouponSaleOrderInterfaceFactory $couponSaleOrderFactory
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CouponUsageOrderExtensionFactory $couponUsageOrderExtensionFactory
     * @param ProductDiscountUsage $productDiscountUsage
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     */
    public function __construct(
        CouponRepositoryInterface $couponRepository,
        CouponSaleOrderInterfaceFactory $couponSaleOrderFactory,
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CouponUsageOrderExtensionFactory $couponUsageOrderExtensionFactory,
        ProductDiscountUsage $productDiscountUsage,
        CouponSaleRepositoryInterface $couponSaleRepository
    ) {
        $this->couponRepository = $couponRepository;
        $this->couponSaleOrderFactory = $couponSaleOrderFactory;
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->couponUsageOrderExtensionFactory = $couponUsageOrderExtensionFactory;
        $this->productDiscountUsage = $productDiscountUsage;
        $this->couponSaleRepository = $couponSaleRepository;
    }

    /**
     * Build coupon usage order extension attribute.
     *
     * @param OrderInterface $order
     * @param string $couponCode
     * @return CouponUsageOrderExtensionInterface|null
     */
    public function build(OrderInterface $order, string $couponCode): ?CouponUsageOrderExtensionInterface
    {
        try {
            /** @var \Ytec\CouponSales\Api\Data\CouponInterface $coupon */
            $coupon = $this->getCouponByCode($couponCode);

            /** @var \Ytec\CouponSales\Api\Data\RuleInterface $rule */
            $rule = $this->ruleRepository->getById($coupon->getRuleId());
        } catch (LocalizedException $exception) {
            return null;
        }

        /** @var CouponUsageOrderExtensionInterface $couponUsageOrderExtension */
        $couponUsageOrderExtension = $this->couponUsageOrderExtensionFactory->create();

        $couponUsageOrderExtension
            ->setData(CouponUsageOrderExtensionInterface::COUPON_CODE, $coupon->getCode())
            ->setData(CouponUsageOrderExtensionInterface::COUPON_TYPE_CODE, $coupon->getCustomCouponType())
            ->setData(
                CouponUsageOrderExtensionInterface::PRODUCT_DISCOUNT_USAGE,
                $this->productDiscountUsage->execute($order, (int)$rule->getRuleId())
            )->setData(
                CouponUsageOrderExtensionInterface::PARTNER_COUPON_SALES,
                $this->getPartnerCouponSale($couponCode)
            );

        return $couponUsageOrderExtension;
    }

    /**
     * Get coupon by code.
     *
     * @param string $couponCode
     * @return CouponInterface|null
     * @throws LocalizedException
     */
    private function getCouponByCode(string $couponCode): ?CouponInterface
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter('code', $couponCode)
        ->create();

        try {
            $couponList = $this->couponRepository->getList($criteria);
            $coupon = $couponList->getItems();
            if (empty($coupon)) {
                throw new LocalizedException(__(self::COUPON_CODE_NOT_FOUND_MESSAGE, $couponCode));
            }

            return array_shift($coupon);
        } catch (LocalizedException $exception) {
            throw new LocalizedException(__(self::COUPON_CODE_NOT_FOUND_MESSAGE, $couponCode));
        }
    }

    /**
     * Get partner coupon sale.
     *
     * @param string $couponCode
     * @return CouponSaleOrderInterface|null
     */
    private function getPartnerCouponSale(string $couponCode): ?CouponSaleOrderInterface
    {
        try {
            $couponSale = $this->couponSaleRepository->getCouponSaleByCode($couponCode);

            /*
             * The coupon sale order limits a few data from the coupon sale model.
             */
            return $this->couponSaleOrderFactory->create()->addData($couponSale->getData());
        } catch (NoSuchEntityException $exception) {
            /** Not a couponsale order. */
            return null;
        }
    }
}
