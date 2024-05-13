<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Command\CouponSale;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Api\Data\CouponInterfaceFactory as CouponFactory;
use Psr\Log\LoggerInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\CouponSaleSaveValidatorInterface;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Model\CouponSaleModelFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleResource;

/**
 * Class SaveCommand
 * @package Ytec\CouponSales\Command\CouponSale
 * Save CouponSale.
 */
class SaveCommand
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CouponSaleModelFactory
     */
    private CouponSaleModelFactory $modelFactory;

    /**
     * @var CouponSaleResource
     */
    private CouponSaleResource $resource;

    /**
     * @var CouponSaleSaveValidatorInterface
     */
    private CouponSaleSaveValidatorInterface $couponSaleSaveValidator;

    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;

    /**
     * @var CouponFactory
     */
    private CouponFactory $couponFactory;

    /**
     * @param LoggerInterface $logger
     * @param CouponSaleModelFactory $modelFactory
     * @param CouponSaleResource $resource
     * @param CouponSaleSaveValidatorInterface $couponSaleSaveValidator
     * @param CouponRepositoryInterface $couponRepository
     * @param CouponFactory $couponFactory
     */
    public function __construct(
        LoggerInterface                  $logger,
        CouponSaleModelFactory           $modelFactory,
        CouponSaleResource               $resource,
        CouponSaleSaveValidatorInterface $couponSaleSaveValidator,
        CouponRepositoryInterface        $couponRepository,
        CouponFactory                    $couponFactory
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->couponSaleSaveValidator = $couponSaleSaveValidator;
        $this->couponRepository = $couponRepository;
        $this->couponFactory = $couponFactory;
    }

    /**
     * Save CouponSale.
     *
     * @param CouponSaleInterface $giftCard
     *
     * @return CouponSaleModel
     * @throws CouldNotSaveException If an error occurs while saving.
     * @throws LocalizedException If validation fails.
     */
    public function execute(CouponSaleInterface $giftCard): CouponSaleModel
    {
        $giftCard->setCode(trim(strtoupper($giftCard->getCode())));
        $this->couponSaleSaveValidator->validate($giftCard);
        try {
            /** @var CouponSaleModel|CouponSaleInterface $model */
            $model = $this->modelFactory->create();
            $model->addData($giftCard->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(CouponSaleInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
                $giftCard->addHistoryLine(
                    __('Coupon Sale created at %1.', (new \DateTime())->format('Y-m-d H:i:s'))->render()
                );
            } else {
                $giftCard->addHistoryLine(
                    __(
                        'Coupon Sale updated at %1: Status - "%2".',
                        (new \DateTime())->format('Y-m-d H:i:s'),
                        $model->getStatus()
                    )->render()
                );
            }

            $model->setHistory($giftCard->getHistory());

            $this->assignCodeForCouponSale($model);

            $this->resource->save($model);
        } catch (AlreadyExistsException $exception) {
            throw new CouldNotSaveException(
                __('Could not save CouponSale: %1', 'The couponsale code already exists.'),
                $exception
            );
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save CouponSale. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(
                __('Could not save CouponSale: %1', $exception->getMessage()),
                $exception
            );
        }

        return $model;
    }

    /**
     * Assign code for Coupon Sale.
     * @param CouponSaleModel $model
     * @return void
     * @throws LocalizedException
     * @throws InputException
     * @throws NoSuchEntityException
     * @noinspection PhpDeprecationInspection
     */
    private function assignCodeForCouponSale(CouponSaleModel $model): void
    {
        /** @var CouponSaleInterface|CouponSaleModel $model */
        if ($model->isObjectNew()) {
            /** @var CouponInterface $coupon */
            $coupon = $this->couponFactory->create();

            $coupon
                ->setRuleId($model->getRuleId())
                ->setCode($model->getCode())
                ->setType(CouponInterface::TYPE_GENERATED)
                ->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'))
                ->setExpirationDate($model->getExpiresAt());
        } else {
            $coupon = $this->couponRepository->getById($model->getCodeId());
            $coupon
                ->setRuleId($model->getRuleId())
                ->setCode($model->getCode())
                ->setExpirationDate($model->getExpiresAt());
        }

        $this->setupTimesUsedForCoupon($model, $coupon);
        $this->couponRepository->save($coupon);
        $model->setCodeId($coupon->getCouponId());
    }

    private function setupTimesUsedForCoupon(CouponSaleModel $model, CouponInterface $coupon): void
    {
        /**
         * Blockage statuses.
         * All statuses that block the coupon usage.
         */
        $blockageStatuses = [
            Status::USED,
            Status::DISABLED,
            Status::DISABLED_BY_PARTNER,
            Status::EXPIRED
        ];

        /**
         * We need to set the times used to the usage limit if the Coupon Sale is blocked.
         * So Magento will not allow the coupon to be used.
         * @var CouponSaleInterface|CouponSaleModel $model
         */
        if (in_array($model->getStatus(), $blockageStatuses)) {
            $coupon->setTimesUsed((int)$coupon->getUsageLimit());
        } elseif ($model->getStatus() === Status::AVAILABLE) {
            $coupon->setTimesUsed(0);
        }
    }
}
