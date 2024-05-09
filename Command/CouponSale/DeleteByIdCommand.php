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
use Magento\Framework\Exception\NoSuchEntityException;
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
class DeleteByIdCommand
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
     * @var DeleteCommand
     */
    private DeleteCommand $deleteCommand;

    /**
     * @param LoggerInterface $logger
     * @param CouponSaleModelFactory $modelFactory
     * @param CouponSaleResource $resource
     * @param DeleteCommand $deleteCommand
     */
    public function __construct(
        LoggerInterface      $logger,
        CouponSaleModelFactory $modelFactory,
        CouponSaleResource   $resource,
        DeleteCommand        $deleteCommand
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->deleteCommand = $deleteCommand;
    }

    /**
     * Delete GiftCard.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var CouponSaleModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, CouponSaleInterface::ENTITY_ID);

            if (!$model->getData(CouponSaleInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __(
                        'Could not find GiftCard with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->deleteCommand->execute($model);
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
