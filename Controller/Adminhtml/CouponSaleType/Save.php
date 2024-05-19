<?php
/**
 * Copyright © 2024 Ytec. All rights reserved.
 *
 * @package    Ytec
 * @moduleName CouponSales
 * @author     Matheus Marqui <matheus.701@live.com>
 */
declare(strict_types=1);

namespace Ytec\CouponSales\Controller\Adminhtml\CouponSaleType;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterface;
use Ytec\CouponSales\Api\Data\CouponSaleTypeInterfaceFactory;
use Ytec\CouponSales\Command\CouponSaleType\SaveCommand;

/**
 * Class Save
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSaleType
 * Save CouponSaleType action controller.
 */
class Save extends BackendAction implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::type_management';

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var SaveCommand
     */
    private SaveCommand $saveCommand;

    /**
     * @var CouponSaleTypeInterfaceFactory
     */
    private CouponSaleTypeInterfaceFactory $entityDataFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveCommand $saveCommand
     * @param CouponSaleTypeInterfaceFactory $entityDataFactory
     */
    public function __construct(
        Context                        $context,
        DataPersistorInterface         $dataPersistor,
        SaveCommand                    $saveCommand,
        CouponSaleTypeInterfaceFactory $entityDataFactory
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
    }

    /**
     * Save CouponSaleType Action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            /** @var CouponSaleTypeInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($params['general']);
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The Coupon Type data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                CouponSaleTypeInterface::ENTITY_ID => $this->getRequest()->getParam(CouponSaleTypeInterface::ENTITY_ID)
                    ?? $this->getRequest()->getParam(CouponSaleTypeInterface::ENTITY_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
