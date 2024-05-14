<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @autor      Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\SalesRule\Api\Data\CouponInterface;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;

/**
 * Class SetCouponSaleAsAvailableOnCancellationObserver
 * @package Ytec\CouponSales\Observer
 */
class SetCouponSaleAsAvailableOnCancellationObserver implements ObserverInterface
{
    /**
     * @var string
     */
    public const COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE =
        'Coupon sale "%1" was made available again due to order cancellation at %2.';

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleFactory;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param CouponRepositoryInterface $couponRepository
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param ModuleConfigurationInterface $moduleConfiguration
     * @param LoggerInterface $logger
     * @param DateTime $dateTime
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        CouponRepositoryInterface $couponRepository,
        CouponSaleInterfaceFactory $couponSaleFactory,
        OrderRepositoryInterface $orderRepository,
        ModuleConfigurationInterface $moduleConfiguration,
        LoggerInterface $logger,
        DateTime $dateTime
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->couponRepository = $couponRepository;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->moduleConfiguration = $moduleConfiguration;
        $this->dateTime = $dateTime;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isModuleEnabledAndConfigured()) {
            return;
        }

        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');

        if ($couponCode = $order->getCouponCode()) {
            $this->processCouponSaleCancellation($order, $couponCode);
        }
    }

    /**
     * Check if the module is enabled and configured to make coupons available on cancellation.
     *
     * @return bool
     */
    private function isModuleEnabledAndConfigured(): bool
    {
        return $this->moduleConfiguration->isEnabled() &&
            $this->moduleConfiguration->shouldMakeAvailableOnCancellation();
    }

    /**
     * Process the coupon cancellation and make the coupon available again.
     *
     * @param Order $order
     * @param string $couponCode
     * @return void
     */
    private function processCouponSaleCancellation(Order $order, string $couponCode): void
    {
        try {
            /** @var CouponSaleModel|CouponSaleInterface $couponSale */
            $couponSale = $this->couponSaleRepository->getCouponSaleByCode($couponCode);
            $relatedCoupon = $this->couponRepository->getById($couponSale->getCodeId());

            $this->logWarningIfTimesUsedIsIncorrect($relatedCoupon, $couponSale, $order);
            $this->updateCouponSaleAndOrder($couponSale, $order, $couponCode);
        } catch (NoSuchEntityException $exception) {
            /** Not a coupon sale coupon, just a native coupon, skip. */
        } catch (LocalizedException $exception) {
            $this->logger->error(
                __(
                    'Error while trying to get coupon %1 by ID %2: %3',
                    $couponCode,
                    $couponSale->getCodeId(),
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * Log a warning if the times used is incorrect.
     *
     * @param CouponInterface $relatedCoupon
     * @param CouponSaleInterface $couponSale
     * @param Order $order
     * @return void
     */
    private function logWarningIfTimesUsedIsIncorrect(
        CouponInterface $relatedCoupon,
        CouponSaleInterface $couponSale,
        Order $order
    ): void {
        if ($relatedCoupon->getTimesUsed() > 0) {
            $this->logger->warning(
                __(
                    'Something weird happened while making the coupon sale "%1" available again due' .
                    ' to order cancellation: Times used of the related coupon model was not decremented before. ' .
                    'Proceeding anyway to make to coupon sale and coupon available again.',
                    $couponSale->getCode()
                )->render(),
                [
                    'coupon_sale_id' => $couponSale->getEntityId(),
                    'order_id' => $order->getEntityId(),
                    'related_coupon' => $relatedCoupon->getData(),
                ]
            );
        }
    }

    /**
     * Update the coupon sale and order.
     *
     * @param CouponSaleInterface $couponSale
     * @param Order $order
     * @param string $couponCode
     * @return void
     * @throws LocalizedException
     * @throws CouldNotSaveException
     */
    private function updateCouponSaleAndOrder(CouponSaleInterface $couponSale, Order $order, string $couponCode): void
    {
        $updatedCouponSale = $this->couponSaleFactory->create();
        $updatedCouponSale->addData($couponSale->getData())
            ->addHistoryLine(
                __(
                    self::COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE,
                    $couponCode,
                    $this->dateTime->gmtDate()
                )->render()
            )->setStatus(Status::AVAILABLE);

        $order->addCommentToStatusHistory(
            __(
                self::COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE,
                $couponCode,
                $this->dateTime->gmtDate()
            )->render()
        );

        $this->couponSaleRepository->save($updatedCouponSale);
        $this->orderRepository->save($order);
    }
}
