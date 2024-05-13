<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Cron;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Model\CouponSaleModel;

/**
 * Class SetCouponSaleStatusToExpired
 * @package Ytec\CouponSales\Cron
 */
class SetCouponSaleStatusToExpired
{
    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

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
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CouponSaleRepositoryInterface $couponSaleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        CouponSaleInterfaceFactory $couponSaleFactory,
        LoggerInterface $logger
    ) {
        $this->couponSaleRepository = $couponSaleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->logger = $logger;
    }

    /**
     * Set Coupon Sales status to "expired" when the expiration date is reached.
     * @return void
     */
    public function execute(): void
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            $this->filterBuilder
                ->setField(CouponSaleInterface::EXPIRES_AT)
                ->setConditionType('lt')
                ->setValue(date('Y-m-d H:i:s'))
            ->create()
        )
            ->create();

        $CouponSales = $this->couponSaleRepository->getList($searchCriteria)->getItems();

        /** @var CouponSaleInterface|CouponSaleModel $giftCard */
        foreach ($CouponSales as $giftCard) {
            if ($giftCard->getStatus() !== Status::AVAILABLE) {
                continue;
            }

            $updatedCouponSale = $this->couponSaleFactory->create()
                ->addData($giftCard->getData())
                ->addHistoryLine(
                    __(
                        'Coupon Sale updated to "expired" at %1 by CRON.',
                        (new \DateTime())->format('Y-m-d H:i:s')
                    )->render()
                )->setStatus(Status::EXPIRED);

            try {
                $this->couponSaleRepository->save($updatedCouponSale);
            } catch (\Exception $exception) {
                $this->logger->error(
                    __(
                        'Could not set Coupon Sale status to "expired" by cron. Original message: %1',
                        $exception->getMessage()
                    ),
                    [
                        'message' => $exception->getMessage(),
                        'exception' => $exception
                    ]
                );
            }
        }
    }
}
