<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Model\Validator;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\SalesRule\Model\RuleRepository;
use Ytec\CouponSales\Api\CodeTemplateValidatorInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\CouponSaleSaveValidatorInterface;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;
use Ytec\CouponSales\Model\Config\Source\Status;

/**
 * Class CouponSaleSaveValidator
 * @package Ytec\CouponSales\Model\Validator
 * Coupon Sale save validator.
 */
class CouponSaleSaveValidator implements CouponSaleSaveValidatorInterface
{
    /**
     * @var RuleRepository
     */
    private RuleRepository $ruleRepository;

    /**
     * @var CodeTemplateValidatorInterface
     */
    private CodeTemplateValidatorInterface $codeTemplateValidator;

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * @param RuleRepository $ruleRepository
     * @param CodeTemplateValidatorInterface $codeTemplateValidator
     * @param ModuleConfigurationInterface $moduleConfiguration
     */
    public function __construct(
        RuleRepository $ruleRepository,
        CodeTemplateValidatorInterface $codeTemplateValidator,
        ModuleConfigurationInterface $moduleConfiguration
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->codeTemplateValidator = $codeTemplateValidator;
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(CouponSaleInterface $couponSale): void
    {
        if ($couponSale->getPartnerSalesPrice() <= 0) {
            throw new LocalizedException(__('Partner sales price must be greater than 0.'));
        }

        /** @var \Ytec\CouponSales\Api\Data\RuleInterface $rule */
        $rule = $this->getRelatedRule($couponSale->getRuleId());

        if (false === (bool)$rule->getIsPartnerSalesRule()) {
            throw new LocalizedException(__('Related rule must be marked as partner sales rule.'));
        }

        if ((int)$rule->getUsesPerCustomer() !== 0) {
            throw new LocalizedException(
                __('Related rule must be have uses per customer as undefined (empty or 0).')
            );
        }

        if ((int)$rule->getUsesPerCoupon() !== 1) {
            throw new LocalizedException(__('Related rule must be have uses per coupon limited to 1.'));
        }

        if (false === $rule->getIsActive()) {
            throw new LocalizedException(__('Related rule must be active.'));
        }

        if (
            $couponSale->getStatus() === Status::AVAILABLE &&
            $couponSale->getExpiresAt() < (new \DateTime())->format('Y-m-d H:i:s')
        ) {
            throw new LocalizedException(
                __('Coupon Sale with status "Available" must have expiration date in the future.')
            );
        }

        if ($this->moduleConfiguration->isCodeTemplateValidationEnabled()) {
            $this->codeTemplateValidator->validate(
                $rule->getName(),
                $couponSale->getCode(),
                $rule->getGiftCardCouponTemplate()
            );
        }
    }

    /**
     * Check if rule assigned to the Coupon Sale exists.
     * @param int $ruleId
     * @return RuleInterface|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getRelatedRule(int $ruleId): ?RuleInterface
    {
        return $this->ruleRepository->getById($ruleId);
    }
}
