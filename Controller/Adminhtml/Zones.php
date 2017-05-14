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

use Eadesigndev\Warehouses\Model\ZoneFactory;
use Eadesigndev\Warehouses\Model\ZoneRegistry;
use Eadesigndev\Warehouses\Model\ZoneRepository;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

abstract class Zones extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE_VIEW = 'Eadesigndev_Zones::zones';
    const ADMIN_RESOURCE_SAVE = 'Eadesigndev_Zones::save';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $zoneModel;

    /**
     * @var ZoneRegistry
     */
    protected $zoneRegistry;

    /**
     * @var ZoneFactory
     */
    protected $zoneFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Zones constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ZoneRepository $zoneModel
     * @param ZoneRegistry $zoneRegistry
     * @param ZoneFactory $zoneFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ZoneRepository $zoneModel,
        ZoneRegistry $zoneRegistry,
        ZoneFactory $zoneFactory,
        DataObjectHelper $dataObjectHelper,
        ForwardFactory $resultForwardFactory
    ) {
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
     * @param Page $resultPage
     * @return Page
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

        if ($id) {
            $model = $this->zoneModel->getById($id);
        } else {
            $model = $this->zoneFactory->create();
        }

        $this->zoneRegistry->push($model);

        /** @var Page $resultPage */
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
