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

namespace Eadesigndev\Warehouses\Controller\Adminhtml\StockItems;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Eadesigndev\Warehouses\Controller\Adminhtml\StockItems
{

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
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
        parent::__construct($context, $resultPageFactory, $itemModel,$itemRegistry);
    }


}