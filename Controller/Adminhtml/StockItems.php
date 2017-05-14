<?php
/*******************************************************************************
 *
 * EaDesign
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_warehouses
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 ******************************************************************************/

namespace Eadesigndev\Warehouses\Controller\Adminhtml;

use Eadesigndev\Warehouses\Model\StockItemsRepository;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

abstract class StockItems extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE_VIEW = 'Eadesigndev_StockItems::stockitems';
    const ADMIN_RESOURCE_SAVE = 'Eadesigndev_StockItems::save';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $itemModel;

    /**
     * StockItems constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param StockItemsRepository $itemModel
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StockItemsRepository $itemModel
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->itemModel = $itemModel;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Eadesigndev_Warehouses::warehouses_list')
            ->addBreadcrumb(__('EaDesign Stock Items'), __('EaDesign Stock Items'));

        return $resultPage;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE_VIEW);
    }
}
