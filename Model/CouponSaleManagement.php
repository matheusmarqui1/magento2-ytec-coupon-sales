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

use Magento\Framework\Exception\CouldNotDeleteException;
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
use Magento\Framework\Stdlib\DateTime\DateTime;

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
    private CouponSaleRepositoryInterface $couponSaleRepository;

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
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * CouponSaleManagement constructor.
     * @param RestResponseInterface $restResponse
     * @param CouponSaleRepositoryInterface $couponsaleRepository
     * @param LoggerInterface $logger
     * @param PartnerName $partnerName
     * @param CouponSaleInterfaceFactory $couponSaleFactory
     * @param ModuleConfigurationInterface $moduleConfiguration
     * @param DateTime $dateTime
     */
    public function __construct(
        RestResponseInterface $restResponse,
        CouponSaleRepositoryInterface $couponsaleRepository,
        LoggerInterface $logger,
        PartnerName $partnerName,
        CouponSaleInterfaceFactory $couponSaleFactory,
        ModuleConfigurationInterface $moduleConfiguration,
        DateTime $dateTime
    ) {
        $this->restResponse = $restResponse;
        $this->couponSaleRepository = $couponsaleRepository;
        $this->logger = $logger;
        $this->partnerName = $partnerName;
        $this->couponSaleFactory = $couponSaleFactory;
        $this->moduleConfiguration = $moduleConfiguration;
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCode(string $code): RestResponseInterface
    {
        if (!$this->isModuleConfigured('isGetVoucherEndpointEnabled')) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        try {
            $couponSale = $this->couponSaleRepository->getCouponSaleByCode($code);
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
        if (!$this->isModuleConfigured('isDeleteVoucherEndpointEnabled')) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        if ($this->moduleConfiguration->isDeleteVoucherSoftDelete()) {
            return $this->disableByCode($code);
        }

        try {
            /** @var CouponSaleModel|CouponSaleInterface $couponSaleModel */
            $couponSaleModel = $this->couponSaleRepository->getCouponSaleByCode($code);

            if ($couponSaleModel->getStatus() === Status::USED) {
                throw new CouldNotDeleteException(__(self::COUPON_SALE_ALREADY_USED_MESSAGE, $code));
            }

            $this->couponSaleRepository->delete($couponSaleModel);
            return $this->restResponse->noContent();
        } catch (NoSuchEntityException $ex) {
            return $this->restResponse->notFound([
                'message' => __(self::COUPON_SALE_NOT_FOUND_MESSAGE, $code)
            ]);
        } catch (CouldNotDeleteException $exception) {
            return $this->restResponse->badRequest([
                'message' => $exception->getMessage()
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
        if (!$this->isModuleConfigured('isCreateVoucherEndpointEnabled')) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        foreach ($couponSales as $couponSale) {
            try {
                $this->createCouponSale($couponSale);
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
     * Create a single coupon sale.
     *
     * @param CouponSaleInterface $couponSale
     * @return void
     * @throws LocalizedException
     */
    private function createCouponSale(CouponSaleInterface $couponSale): void
    {
        $partnerName = $this->partnerName->getPartnerNameFromRequest() ?: 'Partner API';
        $couponSale
            ->setPartnerName($partnerName)
            ->addHistoryLine(
                __(
                    'Coupon Sale created at %1 by %2.',
                    $this->dateTime->gmtDate(),
                    $partnerName
                )->render()
            )->setStatus(Status::AVAILABLE);
        $this->couponSaleRepository->save($couponSale);
    }

    /**
     * {@inheritdoc}
     */
    public function disableByCode(string $code): RestResponseInterface
    {
        if (!$this->isModuleConfigured('isDisableVoucherEndpointEnabled')) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        try {
            $this->processDisableByCode($code);
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

    /**
     * Process the disable action for a coupon sale by code.
     *
     * @param string $code
     * @return void
     * @throws LocalizedException
     */
    private function processDisableByCode(string $code): void
    {
        /** @var CouponSaleModel|CouponSaleInterface $couponSaleModel */
        $couponSaleModel = $this->couponSaleRepository->getCouponSaleByCode($code);

        if ($couponSaleModel->getStatus() === Status::USED) {
            throw new LocalizedException(__(self::COUPON_SALE_ALREADY_USED_MESSAGE, $code));
        }

        $partnerName = $this->partnerName->getPartnerNameFromRequest() ?: 'Partner API';
        $couponSaleUpdated = $this->couponSaleFactory->create()
            ->addData($couponSaleModel->getData())
            ->addHistoryLine(
                __(
                    'Coupon Sale updated to "disabled" at %1 by %2.',
                    $this->dateTime->gmtDate(),
                    $partnerName
                )->render()
            )
            ->setStatus(Status::DISABLED_BY_PARTNER);
        $this->couponSaleRepository->save($couponSaleUpdated);
    }

    /**
     * {@inheritdoc}
     */
    public function disableByCodeInBulk(array $codes): RestResponseInterface
    {
        if (!$this->isModuleConfigured('isBulkDisableVoucherEndpointEnabled')) {
            return $this->restResponse->forbidden([
                'message' => __(self::MODULE_DISABLED_MESSAGE)
            ]);
        }

        $errors = [];

        foreach ($codes as $code) {
            try {
                $this->processDisableByCode($code);
            } catch (\Exception $exception) {
                $errors[] = [
                    'code' => $code,
                    'message' => $exception->getMessage()
                ];
            }
        }

        if (!empty($errors)) {
            return $this->restResponse->badRequest([
                'message' => __(self::BULK_DISABLE_COUPON_SALES_ERROR_MESSAGE),
                'errors' => $errors,
                'total_entries' => count($codes),
                'total_errors' => count($errors),
                'successfully_disabled' => count($codes) - count($errors)
            ]);
        }

        return $this->restResponse->noContent();
    }

    /**
     * Check if the module is configured and enabled for the given endpoint.
     *
     * @param string $endpointEnableConfigMethod
     * @return bool
     */
    private function isModuleConfigured(string $endpointEnableConfigMethod): bool
    {
        return $this->moduleConfiguration->isEnabled() &&
            call_user_func([$this->moduleConfiguration, $endpointEnableConfigMethod]);
    }
}
