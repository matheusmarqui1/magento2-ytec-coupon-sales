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
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Edit
 * @package Ytec\CouponSales\Controller\Adminhtml\CouponSaleType
 * Edit CouponSaleType action controller.
 */
class Edit extends BackendAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Ytec_CouponSales::type_management';

    /**
     * Edit CouponSaleType action.
     *
     * @return Page
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Ytec_CouponSales::type_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Coupon Type'));

        return $resultPage;
    }
}
