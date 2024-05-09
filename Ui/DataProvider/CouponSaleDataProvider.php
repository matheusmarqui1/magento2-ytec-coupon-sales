<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Ui\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Query\CouponSale\GetListQuery;

/**
 * Class GiftCardDataProvider
 * @package Ytec\CouponSales\Ui\DataProvider
 * GiftCard data provider.
 */
class CouponSaleDataProvider extends DataProvider
{
    /**
     * @var GetListQuery
     */
    private GetListQuery $getListQuery;

    /**
     * @var SearchResultFactory
     */
    private SearchResultFactory $searchResultFactory;

    /**
     * @var array
     */
    private array $loadedData = [];

    /**
     * @var PricingHelper
     */
    private PricingHelper $pricingHelper;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param GetListQuery $getListQuery
     * @param SearchResultFactory $searchResultFactory ,
     * @param PricingHelper $pricingHelper
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        GetListQuery $getListQuery,
        SearchResultFactory $searchResultFactory,
        PricingHelper $pricingHelper,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->getListQuery = $getListQuery;
        $this->searchResultFactory = $searchResultFactory;
        $this->pricingHelper = $pricingHelper;
    }

    /**
     * Returns searching result.
     *
     * @return SearchResultInterface
     */
    public function getSearchResult(): SearchResultInterface
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->getListQuery->execute($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            CouponSaleInterface::ENTITY_ID
        );
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }
        $this->loadedData = parent::getData();
        $itemsById = [];

        foreach ($this->loadedData['items'] as $item) {
            $item[CouponSaleInterface::PARTNER_SALES_PRICE] =
                $this->pricingHelper->currency(
                    $item[CouponSaleInterface::PARTNER_SALES_PRICE],
                    true,
                    false
                );
            $itemsById[(int)$item[CouponSaleInterface::ENTITY_ID]] = $item;
        }

        $id = $this->request->getParam(CouponSaleInterface::ENTITY_ID);

        if ($id) {
            $this->loadedData['entity'] = $itemsById[(int)$id];
        }

        return $this->loadedData;
    }
}
