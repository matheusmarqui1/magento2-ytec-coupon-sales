<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model;

use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Command\CouponSale\DeleteByIdCommand;
use Ytec\CouponSales\Command\CouponSale\DeleteCommand;
use Ytec\CouponSales\Command\CouponSale\SaveCommand;
use Ytec\CouponSales\Model\CouponSaleModelFactory as CouponSaleFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollection;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollectionFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleResource;

/**
 * Class CouponSaleRepository
 * @package Ytec\CouponSales\Model
 * CouponSale repository.
 */
class CouponSaleRepository implements CouponSaleRepositoryInterface
{
    /**
     * @var CouponSaleResource
     */
    private CouponSaleResource $resource;

    /**
     * @var CouponSaleFactory
     */
    private CouponSaleFactory $couponSaleFactory;

    /**
     * @var CouponSaleCollectionFactory
     */
    private CouponSaleCollectionFactory $couponSaleCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsFactory
     */
    private SearchResultsFactory $searchResultsFactory;

    /**
     * @var SaveCommand
     */
    private SaveCommand $saveCommand;

    /**
     * @var DeleteByIdCommand
     */
    private DeleteByIdCommand $deleteByIdCommand;

    /**
     * @var DeleteCommand
     */
    private DeleteCommand $deleteCommand;

    /**
     * @param CouponSaleResource $resource
     * @param CouponSaleModelFactory $couponSaleFactory
     * @param CouponSaleCollectionFactory $couponSaleCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory $searchResultsFactory
     * @param SaveCommand $saveCommand
     * @param DeleteByIdCommand $deleteByIdCommand
     * @param DeleteCommand $deleteCommand
     */
    public function __construct(
        CouponSaleResource             $resource,
        CouponSaleFactory              $couponSaleFactory,
        CouponSaleCollectionFactory    $couponSaleCollectionFactory,
        CollectionProcessorInterface   $collectionProcessor,
        SearchResultsFactory           $searchResultsFactory,
        SaveCommand                    $saveCommand,
        DeleteByIdCommand              $deleteByIdCommand,
        DeleteCommand                  $deleteCommand
    ) {
        $this->resource = $resource;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->couponSaleCollectionFactory = $couponSaleCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->saveCommand = $saveCommand;
        $this->deleteByIdCommand = $deleteByIdCommand;
        $this->deleteCommand = $deleteCommand;
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): CouponSaleModel
    {
        /** @var CouponSaleInterface|CouponSaleModel $couponSale */
        $couponSale = $this->couponSaleFactory->create();
        $this->resource->load($couponSale, $id);
        if (!$couponSale->getId()) {
            throw new NoSuchEntityException(__('Unable to find CouponSale with ID "%1"', $id));
        }
        return $couponSale;
    }

    /**
     * {@inheritDoc}
     */
    public function save(CouponSaleInterface $couponsale): CouponSaleModel
    {
        return $this->saveCommand->execute($couponsale);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(CouponSaleModel $couponsale): void
    {
        $this->deleteCommand->execute($couponsale);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteById(int $id): void
    {
        $this->deleteByIdCommand->execute($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getCouponSaleByCode(string $code): CouponSaleModel
    {
        /** @var CouponSaleCollection<CouponSaleInterface> $collection */
        $collection = $this->couponSaleCollectionFactory->create();
        $collection->addFieldToFilter(CouponSaleInterface::CODE, $code)->setPageSize(1);

        if ((int)$collection->getSize() === 0) {
            throw new NoSuchEntityException(__('Unable to find Coupon Sale with code "%1"', $code));
        }

        /** @var CouponSaleInterface|CouponSaleModel $couponSale */
        $couponSale = $collection->getFirstItem();

        return $couponSale;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->couponSaleCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var CouponSaleModel[]|AbstractExtensibleObject[] $items */
        $items = $collection->getItems();
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
