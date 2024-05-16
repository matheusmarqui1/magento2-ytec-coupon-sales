<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Controller\Adminhtml\CouponSale;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RawFactory as ResultRawFactory;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;

/**
 * Class Download
 * @package Ytec\CouponSales\Controller\Adminhtml\Sample
 * Download sample CSV file controller.
 */
class ImportCsvSample extends BackendAction implements HttpGetActionInterface
{
    /**
     * Sample CSV file name
     */
    private const SAMPLE_COUPON_SALES_CSV = 'sample_coupon_sales.csv';

    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * @var ResultRawFactory
     */
    private ResultRawFactory $resultRawFactory;

    /**
     * ImportCsvSample constructor.
     * @param Context $context
     * @param ModuleConfigurationInterface $moduleConfiguration
     * @param ResultRawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        ModuleConfigurationInterface $moduleConfiguration,
        ResultRawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->moduleConfiguration = $moduleConfiguration;
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * @return Raw
     */
    public function execute(): Raw
    {
        return $this->resultRawFactory->create()
            ->setContents($this->generateSampleCsv())
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename=' . self::SAMPLE_COUPON_SALES_CSV);
    }

    /**
     * Generate sample CSV file content.
     *
     * @return string
     */
    private function generateSampleCsv(): string
    {
        $requiredColumns = $this->moduleConfiguration->getImportRequiredFields();

        return implode(',', $requiredColumns) . PHP_EOL;
    }
}
