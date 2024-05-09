<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Config\Source;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\Data\Rule as MagentoRule;
use Ytec\CouponSales\Api\Data\RuleInterface;

/**
 * Class Rule
 * @package Ytec\CouponSales\Model\Config\Source
 */
class Rule implements OptionSourceInterface
{
    private RuleRepositoryInterface $ruleRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private FilterBuilder $filterBuilder;

    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(
                $this->filterBuilder->setField(RuleInterface::IS_PARTNER_SALES_RULE)
                    ->setValue(true)
                    ->create()
            )->addSortOrder(
                MagentoRule::KEY_NAME,
                'ASC'
            )->create();

        /** @var RuleInterface[] $giftCardRules */
        $giftCardRules = $this->ruleRepository->getList($criteria)->getItems();

        return array_map(fn (RuleInterface $rule) => [
            'value' => $rule->getRuleId(),
            'label' => $rule->getName()
        ], $giftCardRules);
    }
}
