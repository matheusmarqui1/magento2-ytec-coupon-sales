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
use Ytec\CouponSales\Command\CouponSale\DeleteCommand;
use Ytec\CouponSales\Model\CouponSaleModel;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollection;
use Ytec\CouponSales\Model\ResourceModel\CouponSaleModel\CouponSaleCollectionFactory;

/**
 * Class MassDelete
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSale
 * Mass delete action controller.
 */
class MassDelete extends BackendAction implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * @var DeleteCommand
     */
    private DeleteCommand $deleteCommand;

    /**
     * @var MassActionFilter
     */
    private MassActionFilter $massActionFilter;

    /**
     * @var CouponSaleCollectionFactory
     */
    private CouponSaleCollectionFactory $collectionFactory;

    /**
     * @param Context $context
     * @param DeleteCommand $deleteCommand
     * @param MassActionFilter $massActionFilter
     * @param CouponSaleCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        DeleteCommand $deleteCommand,
        MassActionFilter $massActionFilter,
        CouponSaleCollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->deleteCommand = $deleteCommand;
        $this->massActionFilter = $massActionFilter;
        $this->collectionFactory = $collectionFactory;
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

            $deleted = 0;
            $failed = 0;

            /** @var CouponSaleModel $item */
            foreach ($collection as $item) {
                try {
                    $this->deleteCommand->execute($item);
                    $deleted++;
                } catch (CouldNotDeleteException $exception) {
                    $failed++;
                }
            }

            if ($deleted > 0) {
                $this->messageManager->addSuccessMessage(
                    __('You have successfully deleted %1 coupon sale entities.', $deleted)
                );
            }

            if ($failed > 0) {
                $this->messageManager->addErrorMessage(
                    __('Failed to delete %1 coupon sale entities.', $failed)
                );
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('An error occurred while processing your request.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
