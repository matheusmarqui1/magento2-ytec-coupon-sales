<?php
/**
 * Copyright (c) 2024 Ytec.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui (matheus.701@live.com)
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Plugin;

use Magento\SalesRule\Model\Spi\CouponResourceInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\Coupon;
use Ytec\CouponSales\Api\Data\RuleInterface;

/**
 * Class AddCustomCouponTypeOnCouponPlugin
 * @package Ytec\CouponSales\Plugin
 */
class AddCustomCouponTypeOnCouponPlugin
{
    /**
     * @var RuleRepositoryInterface
     */
    private RuleRepositoryInterface $ruleRepository;

    /**
     * AddCustomCouponTypeOnCouponPlugin constructor.
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(RuleRepositoryInterface $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * Before save coupon.
     * @param CouponResourceInterface $subject
     * @param AbstractModel $coupon
     * @return array
     */
    public function beforeSave(CouponResourceInterface $subject, AbstractModel $coupon): array
    {
        if (!$coupon->getData(Coupon::KEY_COUPON_ID)) {
            /** @var RuleInterface $rule */
            try {
                $rule = $this->ruleRepository->getById($coupon->getData(Coupon::KEY_RULE_ID));
            } catch (\Exception $exception) {
                return [$coupon];
            }

            $coupon->setData(
                \Ytec\CouponSales\Api\Data\CouponInterface::CUSTOM_COUPON_TYPE,
                $rule->getCouponTypeCode()
            );
        }

        return [$coupon];
    }
}
