<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Command\CouponSaleType;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Model\CouponSaleTypeModel;
use Ytec\CouponSales\Model\CouponSaleTypeModelFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeResource;

/**
 * Class SaveCommand
 * @package Ytec\CouponSales\Command\CouponSaleType
 */
class SaveCommand
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CouponSaleTypeModelFactory
     */
    private CouponSaleTypeModelFactory $modelFactory;

    /**
     * @var CouponSaleTypeResource
     */
    private CouponSaleTypeResource $resource;

    /**
     * @param LoggerInterface $logger
     * @param CouponSaleTypeModelFactory $modelFactory
     * @param CouponSaleTypeResource $resource
     */
    public function __construct(
        LoggerInterface            $logger,
        CouponSaleTypeModelFactory $modelFactory,
        CouponSaleTypeResource     $resource
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save CouponSaleType.
     *
     * @param CouponSaleTypeInterface $couponSaleType
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(CouponSaleTypeInterface $couponSaleType): int
    {
        try {
            /** @var CouponSaleTypeModel $model */
            $model = $this->modelFactory->create();
            $model->addData($couponSaleType->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(CouponSaleTypeInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save CouponSaleType. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save CouponSaleType.'));
        }

        return (int)$model->getData(CouponSaleTypeInterface::ENTITY_ID);
    }
}
