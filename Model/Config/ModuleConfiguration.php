<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Config;

use Magento\Framework\Exception\NoSuchEntityException;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;
use Ytec\Base\Api\Configuration\ConfigurationManagerInterface;

class ModuleConfiguration implements ModuleConfigurationInterface
{
    /**
     * @var ConfigurationManagerInterface
     */
    private ConfigurationManagerInterface $configurationManager;

    /**
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function __construct(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENABLED);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isCodeTemplateValidationEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_VALIDATE_CODE_TEMPLATE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isCreateVoucherEndpointEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_CREATE_VOUCHER_ENABLE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isGetVoucherEndpointEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_GET_VOUCHER_ENABLE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isDeleteVoucherEndpointEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_DELETE_VOUCHER_ENABLE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isDeleteVoucherSoftDelete(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_DELETE_VOUCHER_IS_SOFT_DELETE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isDisableVoucherEndpointEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_DISABLE_VOUCHER_ENABLE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function getSalesIdRegex(): string
    {
        return $this->configurationManager->get(static::XML_PATH_SALES_ID_REGEX);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function getSalesIdExtractionErrorBehavior(): string
    {
        return $this->configurationManager->get(static::XML_PATH_SALES_ID_REGEX_EXTRACTION_ERROR_BEHAVIOR);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function getImportRequiredFields(): array
    {
        $fields = $this->configurationManager->get(static::XML_PATH_IMPORT_REQUIRED_FIELDS);

        return explode(',', $fields);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function isBulkDisableVoucherEndpointEnabled(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_ENDPOINTS_BULK_DISABLE_VOUCHER_ENABLE);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function shouldMakeAvailableOnCancellation(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_MAKE_AVAILABLE_ON_CANCELLATION);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function shouldMakeAvailableOnRefund(): bool
    {
        return $this->configurationManager->isOn(static::XML_PATH_MAKE_AVAILABLE_ON_REFUND);
    }
}
