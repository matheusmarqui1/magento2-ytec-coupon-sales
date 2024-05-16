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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter as MassActionFilter;
use Ytec\CouponSales\Model\Config\Source\Status;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Api\CouponSaleRepositoryInterface;
use Ytec\CouponSales\Api\Data\CouponSaleInterfaceFactory;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollection;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollectionFactory;

/**
 * Class MassDelete
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSale
 * Mass delete action controller.
 */
class MassDisable extends BackendAction implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * @var MassActionFilter
     */
    private MassActionFilter $massActionFilter;

    /**
     * @var CouponSaleCollectionFactory
     */
    private CouponSaleCollectionFactory $collectionFactory;

    /**
     * @var CouponSaleRepositoryInterface
     */
    private CouponSaleRepositoryInterface $couponSaleRepository;

    /**
     * @var CouponSaleInterfaceFactory
     */
    private CouponSaleInterfaceFactory $couponSaleInterfaceFactory;

    /**
     * @param Context $context
     * @param MassActionFilter $massActionFilter
     * @param CouponSaleCollectionFactory $collectionFactory
     * @param CouponSaleRepositoryInterface $couponSaleRepository
     * @param CouponSaleInterfaceFactory $couponSaleInterfaceFactory
     */
    public function __construct(
        Context $context,
        MassActionFilter $massActionFilter,
        CouponSaleCollectionFactory $collectionFactory,
        CouponSaleRepositoryInterface $couponSaleRepository,
        CouponSaleInterfaceFactory $couponSaleInterfaceFactory,
    ) {
        parent::__construct($context);
        $this->massActionFilter = $massActionFilter;
        $this->collectionFactory = $collectionFactory;
        $this->couponSaleRepository = $couponSaleRepository;
        $this->couponSaleInterfaceFactory = $couponSaleInterfaceFactory;
    }

    /**
     * Mass delete action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        try {
            $collection = $this->massActionFilter->getCollection($this->collectionFactory->create());

            if ($collection->getSize() === 0) {
                $this->messageManager->addErrorMessage(__('Please select item(s).'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }

            $disabled = 0;
            $failed = 0;

            /** @var CouponSaleModel $item */
            foreach ($collection as $item) {
                try {
                    $updatedCouponSale = $this->couponSaleInterfaceFactory->create()
                        ->addData($item->getData())
                        ->setStatus(Status::DISABLED);

                    $this->couponSaleRepository->save($updatedCouponSale);

                    $disabled++;
                } catch (\Exception $exception) {
                    $failed++;
                }
            }

            if ($disabled > 0) {
                $this->messageManager->addSuccessMessage(
                    __('You have successfully disabled %1 coupon sale entity(ies).', $disabled)
                );
            }

            if ($failed > 0) {
                $this->messageManager->addErrorMessage(
                    __('Failed to disable %1 coupon sale entity(ies).', $failed)
                );
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('An error occurred while processing your request.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
