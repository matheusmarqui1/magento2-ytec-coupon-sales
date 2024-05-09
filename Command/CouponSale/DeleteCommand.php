<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Command\CouponSale;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Model\CouponSaleModelFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleResource;

/**
 * Class DeleteByIdCommand
 * @package Ytec\CouponSales\Command\GiftCard
 * Delete GiftCard by id.
 */
class DeleteCommand
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CouponSaleModelFactory
     */
    private CouponSaleModelFactory $modelFactory;

    /**
     * @var CouponSaleResource
     */
    private CouponSaleResource $resource;

    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;

    /**
     * @param LoggerInterface $logger
     * @param CouponSaleModelFactory $modelFactory
     * @param CouponSaleResource $resource
     * @param CouponRepositoryInterface $couponRepository
     */
    public function __construct(
        LoggerInterface           $logger,
        CouponSaleModelFactory    $modelFactory,
        CouponSaleResource        $resource,
        CouponRepositoryInterface $couponRepository
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->couponRepository = $couponRepository;
    }

    /**
     * Delete GiftCard.
     *
     * @param CouponSaleModel $giftCardModel
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(CouponSaleModel $giftCardModel): void
    {
        try {
            /** @var CouponSaleModel|CouponSaleInterface $giftCardModel */
            $this->couponRepository->deleteById($giftCardModel->getCodeId());
            $this->resource->delete($giftCardModel);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete GiftCard. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete GiftCard.'));
        }
    }
}
