<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Setup\Patch\Data;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Ytec\CouponSales\Command\CouponSaleType\SaveCommand as CouponTypeSaveCommand;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterfaceFactory;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes.
 */
class AddDefaultCouponTypePatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var CouponTypeSaveCommand
     */
    private CouponTypeSaveCommand $saveCommand;

    /**
     * @var CouponSaleTypeInterfaceFactory
     */
    private CouponSaleTypeInterfaceFactory $couponSaleTypeFactory;

    /**
     * Constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CouponTypeSaveCommand $saveCommand
     * @param CouponSaleTypeInterfaceFactory $couponSaleTypeFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CouponTypeSaveCommand $saveCommand,
        CouponSaleTypeInterfaceFactory $couponSaleTypeFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->saveCommand = $saveCommand;
        $this->couponSaleTypeFactory = $couponSaleTypeFactory;
    }

    /**
     * Do Upgrade.
     *
     * @return AddDefaultCouponTypePatch
     * @throws CouldNotSaveException
     */
    public function apply(): self
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var CouponSaleTypeInterface $couponType */
        $couponType = $this->couponSaleTypeFactory->create(
            [
                'data' => [
                    'code' => 'default',
                    'label' => 'Default',
                    'is_active' => true
                ]
            ]
        );

        $this->saveCommand->execute($couponType);

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * Get Dependencies.
     *
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
