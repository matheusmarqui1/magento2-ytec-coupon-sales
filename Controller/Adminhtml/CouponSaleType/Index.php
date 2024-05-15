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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSaleType
 * Index CouponSaleType action controller.
 */
class Index extends BackendAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::type_management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Ytec_CouponSales::type_management');
        $resultPage->addBreadcrumb(__('Coupon Sale Type'), __('Coupon Sale Type'));
        $resultPage->addBreadcrumb(__('Manage Coupon Sale Types'), __('Manage Coupon Sale Types'));
        $resultPage->getConfig()->getTitle()->prepend(__('Coupon Sale Type List'));

        return $resultPage;
    }
}
