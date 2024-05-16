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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InvalidArgumentException;
use Ytec\CouponSales\Api\CouponSaleCsvImporterInterface;
use Ytec\CouponSales\Api\Data\CouponSaleImportResultInterface;
use Ytec\CouponSales\Block\Import\ErrorDetails;

/**
 * Class NewAction
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSale
 * Create new CouponSale controller.
 */
class ProcessImport extends BackendAction implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Field name of the file input/file uploader UI component.
     */
    public const IMPORT_FILE_FIELD_NAME = 'import_file';

    /**
     * Field name of the import behavior select UI component.
     */
    public const IMPORT_BEHAVIOR_FIELD_NAME = 'import_behavior';

    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * @var CouponSaleCsvImporterInterface
     */
    private CouponSaleCsvImporterInterface $couponSaleCsvImporter;

    /**
     * ProcessImport constructor.
     * @param Context $context
     * @param CouponSaleCsvImporterInterface $couponSaleCsvImporter
     */
    public function __construct(Context $context, CouponSaleCsvImporterInterface $couponSaleCsvImporter)
    {
        parent::__construct($context);
        $this->couponSaleCsvImporter = $couponSaleCsvImporter;
    }

    /**
     * Process new CouponSale import action.
     *
     * @return ResultInterface
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function execute(): ResultInterface
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $files = $this->getRequest()->getFiles('general');
        $params = $this->getRequest()->getParam('general');
        $importBehavior = $params[self::IMPORT_BEHAVIOR_FIELD_NAME];

        if (empty($files)) {
            $this->messageManager->addErrorMessage(__('Please select a file to import.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $file = $files[self::IMPORT_FILE_FIELD_NAME];

        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->messageManager->addErrorMessage(__('An error occurred while uploading the file.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $importResult = $this->couponSaleCsvImporter->import($file['tmp_name'], $importBehavior);
        } catch (InvalidArgumentException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        if ($importResult === null) {
            $this->messageManager->addErrorMessage(__('An error occurred while importing the file.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        if ($importResult->getErrorRowsQuantity() > 0) {
            return $this->forward($importResult, $importBehavior);
        }

        $this->messageManager->addSuccessMessage(__('The file has been imported successfully.'));
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }

    /**
     * @param CouponSaleImportResultInterface $importResult
     * @param mixed $importBehavior
     * @return Forward
     */
    public function forward(CouponSaleImportResultInterface $importResult, mixed $importBehavior): Forward
    {
        $forward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        $forward
            ->setParams(['import_result' => $importResult, 'import_behavior' => $importBehavior])
            ->forward('importErrorDetails');

        return $forward;
    }
}
