<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Model\Config\ModuleConfiguration;

/**
 * Class SalesId
 * @package Ytec\CouponSales\Ui\Component\Listing\Column
 * SalesId column for CouponSales listing.
 */
class SalesId extends Column
{
    /**
     * Sales id column.
     */
    public const SALES_ID = 'sales_id';

    /**
     * @var ModuleConfiguration
     */
    private ModuleConfiguration $moduleConfiguration;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ModuleConfiguration $moduleConfiguration,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[static::SALES_ID] = $this->getSalesId($item[CouponSaleInterface::CODE]) ?? 'N/A';
            }
        }
        return $dataSource;
    }


    /**
     * Get sales id from coupon code.
     *
     * @param string $code
     * @return string|null
     */
    public function getSalesId(string $code): ?string
    {
        try {
            $pattern = $this->moduleConfiguration->getSalesIdRegex();
        } catch (NoSuchEntityException $exception) {
            $pattern = '/\d{6}(?=\D*$)/';
        }
        $matches = [];

        preg_match($pattern, $code, $matches);

        return $matches[0] ?: null;
    }
}
