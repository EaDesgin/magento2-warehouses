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


abstract class StockItems extends \Magento\Backend\App\Action
{

    CONST ADMIN_RESOURCE_VIEW = 'Eadesigndev_StockItems::stockitems';
    CONST ADMIN_RESOURCE_SAVE = 'Eadesigndev_StockItems::save';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $itemModel;

    protected $itemRegistry;

    /**
     * StockItems constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Eadesigndev\Warehouses\Model\StockItemsRepository $itemModel
     * @param \Eadesigndev\Warehouses\Model\StockItemsRegistry $itemRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Eadesigndev\Warehouses\Model\StockItemsRepository $itemModel,
        \Eadesigndev\Warehouses\Model\StockItemsRegistry $itemRegistry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->itemModel = $itemModel;
        $this->itemRegistry = $itemRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Eadesigndev_Warehouses::warehouses_list')
            ->addBreadcrumb(__('EaDesign Stock Items'), __('EaDesign Stock Items'));

        return $resultPage;
    }


    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');

        if (!$id) {
            //$model =  todo create empty model with factory
        }

        $model = $this->itemModel->getById($id);

        $this->itemRegistry->push($model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('EaDesign Stock Items'));

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