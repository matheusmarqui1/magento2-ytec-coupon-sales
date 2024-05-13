<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Ytec\Base\Model\Rest\RestResponse;
use Ytec\CouponSales\Api\CouponSaleRuleManagementInterface;
use Ytec\Base\Api\Rest\RestResponseInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Ytec\CouponSales\Api\Data\RuleInterface;

/**
 * Class CouponSaleRuleManagement
 * @package Ytec\CouponSales\Model
 */
class CouponSaleRuleManagement implements CouponSaleRuleManagementInterface
{
    /**
     * @var RestResponse
     */
    private RestResponse $restResponse;

    /**
     * @var RuleRepositoryInterface
     */
    private RuleRepositoryInterface $ruleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param RestResponse $restResponse
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        RestResponse $restResponse,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->restResponse = $restResponse;
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $ruleId): RestResponseInterface
    {
        try {
            /** @var RuleInterface $couponSaleRule */
            $couponSaleRule = $this->ruleRepository->getById($ruleId);
            if (!$couponSaleRule->getIsPartnerSalesRule()) {
                return $this->restResponse->badRequest(
                    [
                        'message' => __(self::RULE_IS_NOT_COUPON_SALE_RULE_MESSAGE, $ruleId)->render()
                    ]
                );
            }

            return $this->restResponse->ok($couponSaleRule->__toArray());
        } catch (NoSuchEntityException $exception) {
            return $this->restResponse->notFound(
                [
                    'message' => __(self::COUPON_SALE_RULE_NOT_FOUND_MESSAGE, $ruleId)->render()
                ]
            );
        } catch (LocalizedException $exception) {
            return $this->restResponse->internalError(
                [
                    'message' => __(
                        self::COUPON_SALE_RULE_FETCH_ERROR_MESSAGE,
                        $ruleId,
                        $exception->getMessage()
                    )->render()
                ]
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getListing(): RestResponseInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter($this->filterBuilder->setField(
                RuleInterface::IS_PARTNER_SALES_RULE)->setValue(1)->create()
            )
        ->create();

        try {
            $searchResults = $this->ruleRepository->getList($searchCriteria);
        } catch (LocalizedException $exception) {
            return $this->restResponse->internalError(
                [
                    'message' => $exception->getMessage()
                ]
            );
        }

        return $this->restResponse->ok(
            [
                'items' => array_map(
                    function (RuleInterface $rule) {
                        return $rule->__toArray();
                    },
                    $searchResults->getItems()
                )
            ]
        );
    }
}
