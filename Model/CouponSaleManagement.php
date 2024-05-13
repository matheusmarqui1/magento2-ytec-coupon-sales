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
use Psr\Log\LoggerInterface;
use Ytec\Base\Api\Rest\RestResponseInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleManagementInterface;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\ModuleConfigurationInterface;
use Ytec\CouponSales\Helper\PartnerName;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Model\Data\CouponSaleData;

/**
 * Class CouponSaleManagement
 * @package Ytec\CouponSales\Model
 */
class CouponSaleManagement implements CouponSaleManagementInterface
{
    /**
     * @var RestResponseInterface
     */
    private RestResponseInterface $restResponse;

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponsaleRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var PartnerName
     */
    private PartnerName $partnerName;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleFactory;

    /**
     * @var ModuleConfigurationInterface
     */
    private ModuleConfigurationInterface $moduleConfiguration;

    /**
     * CouponSaleManagement constructor.
     * @param RestResponseInterface $restResponse
     * @param CouponSaleRepositoryInterface $couponsaleRepository
     * @param LoggerInterface $logger
     * @param PartnerName $partnerName
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param ModuleConfigurationInterface $moduleConfiguration
     */
    public function __construct(
        RestResponseInterface $restResponse,
        CouponSaleRepositoryInterface $couponsaleRepository,
        LoggerInterface $logger,
        PartnerName $partnerName,
        CouponSaleInterfaceFactory $couponSaleFactory,
        ModuleConfigurationInterface $moduleConfiguration
    ) {
        $this->restResponse = $restResponse;
        $this->couponsaleRepository = $couponsaleRepository;
        $this->logger = $logger;
        $this->partnerName = $partnerName;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCode(string $code): RestResponseInterface
    {
        if (!$this->moduleConfiguration->isEnabled() || !$this->moduleConfiguration->isGetVoucherEndpointEnabled()) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        try {
            $couponSale = $this->couponsaleRepository->getCouponSaleByCode($code);
            return $this->restResponse->ok($couponSale->getData());
        } catch (NoSuchEntityException $ex) {
            return $this->restResponse->notFound([
                'message' => __(self::COUPON_SALE_NOT_FOUND_MESSAGE, $code)
            ]);
        } catch (\Exception $ex) {
            return $this->restResponse->internalError([
                'message' => __(self::COUPON_SALE_FETCH_ERROR_MESSAGE, $code, $ex->getMessage())
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByCode(string $code): RestResponseInterface
    {
        if (!$this->moduleConfiguration->isEnabled() || !$this->moduleConfiguration->isDeleteVoucherEndpointEnabled()) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        /**
         * If the soft delete is enabled, we just disable the Coupon Sale instead of deleting it.
         */
        if ($this->moduleConfiguration->isDeleteVoucherSoftDelete()) {
            return $this->disableByCode($code);
        }

        try {
            $couponSale = $this->couponsaleRepository->getCouponSaleByCode($code);
            $this->couponsaleRepository->delete($couponSale);
            return $this->restResponse->noContent();
        } catch (NoSuchEntityException $ex) {
            return $this->restResponse->notFound([
                'message' => __(self::COUPON_SALE_NOT_FOUND_MESSAGE, $code)
            ]);
        } catch (\Exception $ex) {
            return $this->restResponse->internalError([
                'message' => __(self::COUPON_SALE_DELETE_ERROR_MESSAGE, $code, $ex->getMessage())
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createCouponSales(array $couponSales): RestResponseInterface
    {
        if (!$this->moduleConfiguration->isEnabled() || !$this->moduleConfiguration->isCreateVoucherEndpointEnabled()) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        foreach ($couponSales as $couponSale) {
            try {
                $couponSale
                    ->setPartnerName($this->partnerName->getPartnerNameFromRequest())
                    ->addHistoryLine(
                        __(
                            'Coupon Sale created at %1 by %2.',
                            (new \DateTime())->format('Y-m-d H:i:s'),
                            $this->partnerName->getPartnerNameFromRequest() ?: 'Partner API'
                        )->render()
                    )->setStatus(Status::AVAILABLE);
                $this->couponsaleRepository->save($couponSale);
            } catch (NoSuchEntityException $ex) {
                return $this->restResponse->notFound([
                    'message' => __(self::RULE_NOT_FOUND_MESSAGE, $couponSale->getRuleId(), $couponSale->getCode())
                ]);
            } catch (LocalizedException $ex) {
                return $this->restResponse->badRequest([
                    'message' => $ex->getMessage()
                ]);
            } catch (\Exception $ex) {
                $this->logger->error(
                    __(self::COUPON_SALE_SAVE_ERROR_MESSAGE, $couponSale->getCode(), $ex->getMessage())->render()
                );
                return $this->restResponse->internalError([
                    'message' => __(self::COUPON_SALE_SAVE_ERROR_MESSAGE, $couponSale->getCode(), $ex->getMessage())
                ]);
            }
        }

        return $this->restResponse->created([
            'message' => __(self::COUPON_SALES_SUCCESS_MESSAGE)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function disableByCode(string $code): RestResponseInterface
    {
        if (!$this->moduleConfiguration->isEnabled() || !$this->moduleConfiguration->isDisableVoucherEndpointEnabled()) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        try {
            /** @var CouponSaleModel|CouponSaleInterface $couponSaleModel */
            $couponSaleModel = $this->couponsaleRepository->getCouponSaleByCode($code);

            if ($couponSaleModel->getStatus() === Status::USED) {
                return $this->restResponse->badRequest(
                    [
                        'message' => __(self::COUPON_SALE_ALREADY_USED_MESSAGE, $code)
                    ]
                );
            }

            /** @var CouponSaleData $couponSaleUpdated */
            $couponSaleUpdated = $this->couponSaleFactory->create()
                ->addData($couponSaleModel->getData())
                ->addHistoryLine(
                    __(
                        'Coupon Sale updated to "disabled" at %1 by %2.',
                        (new \DateTime())->format('Y-m-d H:i:s'),
                        $this->partnerName->getPartnerNameFromRequest() ?: 'Partner API'
                    )->render()
                )
                ->setStatus(Status::DISABLED_BY_PARTNER);
            $this->couponsaleRepository->save($couponSaleUpdated);

            return $this->restResponse->noContent();
        } catch (NoSuchEntityException $ex) {
            return $this->restResponse->notFound([
                'message' => __(self::COUPON_SALE_NOT_FOUND_MESSAGE, $code)
            ]);
        } catch (\Exception $ex) {
            return $this->restResponse->internalError([
                'message' => __(self::COUPON_SALE_SAVE_ERROR_MESSAGE, $code, $ex->getMessage())
            ]);
        }
    }
}
