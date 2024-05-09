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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Ytec\CouponSales\Api\Data\CouponSaleInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;

/**
 * Class Save
 * @package Ytec\CouponSales\Controller\Adminhtml\GiftCard
 * Save GiftCard action controller.
 */
class Save extends BackendAction implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $entityDataFactory;

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CouponSaleInterfaceFactory $entityDataFactory
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     */
    public function __construct(
        Context                       $context,
        DataPersistorInterface        $dataPersistor,
        CouponSaleInterfaceFactory    $entityDataFactory,
        CouponSaleRepositoryInterface $couponSaleRepository
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->entityDataFactory = $entityDataFactory;
        $this->couponSaleRepository = $couponSaleRepository;
    }

    /**
     * Save GiftCard Action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            /** @var CouponSaleInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($params['general']);

            $this->couponSaleRepository->save($entityModel);

            $this->messageManager->addSuccessMessage(
                __('The Coupon Sale data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);
            return $resultRedirect->setPath('*/*/edit', [
                CouponSaleInterface::ENTITY_ID => $params['general'][CouponSaleInterface::ENTITY_ID]
                        ?? $this->getRequest()->getParam(CouponSaleInterface::ENTITY_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
