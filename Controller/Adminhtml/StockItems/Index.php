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

namespace Eadesigndev\Warehouses\Controller\Adminhtml\StockItems;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Eadesigndev\Warehouses\Controller\Adminhtml\StockItems
{

//    /**
//     * @var \Magento\Framework\View\Result\PageFactory
//     */
//    protected $resultPageFactory;
//
//    /**
//     * @param \Magento\Backend\App\Action\Context $context
//     * @param \Magento\Framework\Registry $coreRegistry
//     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
//     */
//    public function __construct(
//        \Magento\Backend\App\Action\Context $context,
//        \Eadesigndev\Warehouses\Model\ZoneRepository $zoneModel,
//        \Magento\Framework\View\Result\PageFactory $resultPageFactory
//    )
//    {
//        $this->resultPageFactory = $resultPageFactory;
//        parent::__construct($context, $zoneModel);
//    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('EaDesign Stock Items'));

        return $resultPage;
    }
}