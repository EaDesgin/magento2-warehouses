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

namespace Eadesigndev\Warehouses\Controller\Adminhtml\Zones;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Eadesigndev\Warehouses\Controller\Adminhtml\Zones
{

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Eadesigndev\Warehouses\Model\ZoneRepository $zoneModel
     * @param \Eadesigndev\Warehouses\Model\ZoneRegistry $zoneRegistry
     * @param \Eadesigndev\Warehouses\Model\ZoneFactory $zoneFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
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
        parent::__construct(
            $context,
            $resultPageFactory,
            $zoneModel,
            $zoneRegistry,
            $zoneFactory,
            $dataObjectHelper,
            $resultForwardFactory
        );
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
            $model = $this->zoneModel->getByEditId($id);
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


}