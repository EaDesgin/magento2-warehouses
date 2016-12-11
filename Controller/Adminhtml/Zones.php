<?php
/**
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
 */

namespace Eadesigndev\Warehouses\Controller\Adminhtml;


abstract class Zones extends \Magento\Backend\App\Action
{

    CONST ADMIN_RESOURCE_VIEW = 'Eadesigndev_Zones::zones';
    CONST ADMIN_RESOURCE_SAVE = 'Eadesigndev_Zones::save';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $zoneModel;

    /**
     * @var \Eadesigndev\Warehouses\Model\ZoneRegistry
     */
    protected $zoneRegistry;

    protected $zoneFactory;

    protected $dataObjectHelper;

    protected $resultForwardFactory;

    /**
     * Zones constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Eadesigndev\Warehouses\Model\ZoneRepository $zoneModel
     * @param \Eadesigndev\Warehouses\Model\ZoneRegistry $zoneRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Eadesigndev\Warehouses\Model\ZoneRepository $zoneModel,
        \Eadesigndev\Warehouses\Model\ZoneRegistry $zoneRegistry,
        \Eadesigndev\Warehouses\Model\ZoneFactory $zoneFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->zoneModel = $zoneModel;
        $this->zoneRegistry = $zoneRegistry;
        $this->zoneFactory = $zoneFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->resultForwardFactory = $resultForwardFactory;
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
            ->addBreadcrumb(__('EaDesign Zones'), __('EaDesign Zones'));

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

        if ($id){
            $model = $this->zoneModel->getById($id);
        }

        if (!$id || $model->getId()) {
            $model = $this->zoneFactory->create();
        }

        $this->zoneRegistry->push($model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('EaDesign Zones'));

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