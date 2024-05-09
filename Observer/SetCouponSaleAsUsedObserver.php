<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.marqui@photoweb.fr)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Model\Config\Source\Status;

/**
 * Class SetCouponSaleAsUsedObserver
 * @package Ytec\CouponSales\Observer
 */
class SetCouponSaleAsUsedObserver implements ObserverInterface
{
    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        CouponSaleInterfaceFactory $couponSaleFactory,
        LoggerInterface $logger
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->logger = $logger;
    }

    /**
     * Set Coupon Sale as used when order is placed with a Coupon Sale.
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getData('order');

        if ($order->getCouponCode()) {
            try {
                $couponSale = $this->couponSaleRepository->getCouponSaleByCode($order->getCouponCode());
                $couponSaleUpdated = $this->couponSaleFactory->create()
                    ->addData($couponSale->getData())
                    ->addHistoryLine(
                        __('Coupon Sale used on order %1.', $order->getIncrementId())->render()
                    )->setStatus(Status::USED);
                $this->couponSaleRepository->save($couponSaleUpdated);
            } catch (NoSuchEntityException $exception) {
                /** Not a coupon sale, just a native Magento coupon. */
                return;
            } catch (CouldNotSaveException|LocalizedException $exception) {
                $this->logger->error(
                    __('Unable to set Coupon Sale as used. Error: %1', $exception->getMessage())->render(),
                    ['exception' => $exception]
                );
            }
        }
    }
}
