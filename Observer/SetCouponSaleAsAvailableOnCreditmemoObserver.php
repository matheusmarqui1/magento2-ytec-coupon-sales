<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order\Creditmemo;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;

/**
 * Class SetCouponSaleAsAvailableOnCreditmemoObserver
 * @package Ytec\CouponSales\Observer
 */
class SetCouponSaleAsAvailableOnCreditmemoObserver implements ObserverInterface
{
    /**
     * @var string
     */
    public const COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE =
        'Coupon Sale "%1" was made available again due to order credit memo at %2.';

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * SetCouponSaleAsAvailableOnCreditmemoObserver constructor.
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param ModuleConfigurationInterface $moduleConfiguration
     * @param LoggerInterface $logger
     * @param DateTime $dateTime
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        OrderRepositoryInterface $orderRepository,
        CouponSaleInterfaceFactory $couponSaleFactory,
        ModuleConfigurationInterface $moduleConfiguration,
        LoggerInterface $logger,
        DateTime $dateTime
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->orderRepository = $orderRepository;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->moduleConfiguration = $moduleConfiguration;
        $this->logger = $logger;
        $this->dateTime = $dateTime;
    }

    /**
     * Make coupon sale available again when a credit memo is created.
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isModuleEnabledAndConfigured()) {
            return;
        }

        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getData('creditmemo');

        if (!$this->isValidCreditmemo($creditmemo)) {
            return;
        }

        $order = $creditmemo->getOrder();
        if ($this->isOrderFullyRefunded($order)) {
            $couponCode = $order->getCouponCode();
            if ($couponCode) {
                $this->makeCouponSaleAvailableAgain($order, $couponCode);
            }
        }
    }

    /**
     * Check if the module is enabled and configured to make coupons available on refund.
     *
     * @return bool
     */
    private function isModuleEnabledAndConfigured(): bool
    {
        return $this->moduleConfiguration->isEnabled() &&
            $this->moduleConfiguration->shouldMakeAvailableOnRefund();
    }

    /**
     * Validate the credit memo.
     * It must be a new credit memo.
     *
     * @param Creditmemo|null $creditmemo
     * @return bool
     */
    private function isValidCreditmemo(?Creditmemo $creditmemo): bool
    {
        return $creditmemo && !$creditmemo->getId();
    }

    /**
     * Check if the order is fully refunded.
     *
     * @param Order $order
     * @return bool
     */
    private function isOrderFullyRefunded(Order $order): bool
    {
        return $order->getTotalRefunded() >= $order->getGrandTotal();
    }

    /**
     * Make the coupon sale available again.
     *
     * @param Order $order
     * @param string $couponCode
     * @return void
     */
    private function makeCouponSaleAvailableAgain(Order $order, string $couponCode): void
    {
        try {
            $couponSale = $this->couponSaleRepository->getCouponSaleByCode($couponCode);
            $updatedCouponSale = $this->couponSaleFactory->create();
            $updatedCouponSale->addData($couponSale->getData())
                ->setStatus(Status::AVAILABLE)
                ->addHistoryLine(
                    __(
                        self::COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE,
                        $couponCode,
                        $this->dateTime->gmtDate()
                    )->render()
                );

            $order->addCommentToStatusHistory(
                __(
                    self::COUPON_SALE_WAS_MADE_AVAILABLE_AGAIN_MESSAGE,
                    $couponCode,
                    $this->dateTime->gmtDate()
                )->render()
            );

            $this->couponSaleRepository->save($updatedCouponSale);
            $this->orderRepository->save($order);
        } catch (NoSuchEntityException $exception) {
            /** Not a coupon sale, just a native Magento coupon, skip. */
        } catch (CouldNotSaveException|LocalizedException $exception) {
            $this->logger->error(
                __(
                    'Unable to set Coupon Sale %1 as available after credit memo. Error: %2',
                    $couponCode,
                    $exception->getMessage()
                )->render(),
                ['exception' => $exception, 'order_id' => $order->getEntityId()]
            );
        }
    }
}
