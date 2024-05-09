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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package Ytec\CouponSales\Controller\Adminhtml\GiftCard
 * GiftCard list controller.
 */
class Index extends BackendAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Ytec_CouponSales::management');
        $resultPage->addBreadcrumb(__('Coupon Sale'), __('Coupon Sale'));
        $resultPage->addBreadcrumb(__('Manage Coupon Sales'), __('Manage Coupon Sales'));
        $resultPage->getConfig()->getTitle()->prepend(__('Coupon Sale List'));

        return $resultPage;
    }
}
