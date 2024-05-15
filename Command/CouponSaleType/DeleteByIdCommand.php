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
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Model\CouponSaleTypeModel;
use Ytec\CouponSales\Model\CouponSaleTypeModelFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleTypeResource;

/**
 * Class DeleteByIdCommand
 * @package Ytec\CouponSales\Command\CouponSaleType
 */
class DeleteByIdCommand
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
     * Delete CouponSaleType.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var CouponSaleTypeModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, CouponSaleTypeInterface::ENTITY_ID);

            if (!$model->getData(CouponSaleTypeInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find CouponSaleType with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete CouponSaleType. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete CouponSaleType.'));
        }
    }
}
