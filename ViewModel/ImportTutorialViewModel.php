<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;
use Ytec\CouponSales\Model\Config\Source\ImportBehavior;

/**
 * Class ImportTutorialViewModel
 * @package Ytec\CouponSales\ViewModel
 * ImportTutorial view model.
 */
class ImportTutorialViewModel implements ArgumentInterface
{
    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * @var ImportBehavior
     */
    private ImportBehavior $importBehavior;

    /**
     * ImportTutorialViewModel constructor.
     * @param ModuleConfigurationInterface $moduleConfiguration
     * @param ImportBehavior $importBehavior
     */
    public function __construct(
        ModuleConfigurationInterface $moduleConfiguration,
        ImportBehavior $importBehavior
    ) {
        $this->moduleConfiguration = $moduleConfiguration;
        $this->importBehavior = $importBehavior;
    }

    /**
     * Get the import required CSV columns.
     *
     * @return string[]
     */
    public function getRequiredColumns(): array
    {
        return $this->moduleConfiguration->getImportRequiredFields();
    }

    /**
     * Get the available import behaviors.
     *
     * @return array
     */
    public function getAvailableImportBehaviors(): array
    {
        return $this->importBehavior->toOptionArray();
    }
}
