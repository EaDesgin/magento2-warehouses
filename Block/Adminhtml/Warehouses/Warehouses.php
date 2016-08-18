<?php

/**
 * EaDesgin
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
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Warehouses\Block\Adminhtml\Warehouses;

class Warehouses extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * @return void;
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_warehouses';
        $this->_blockGroup = 'Eadesigndev_Warehouses';

        $this->_headerText = __('Warehouses');
        $this->_addButtonLabel = __('Add New Warehouse');
        parent::_construct();
        $this->buttonList->add(
            'warehouse_apply',
            [
                'label' => __('Warehouse'),
                'onclick' => "location.href='" . $this->getUrl('warehouses/*/warehouses') . "'",
                'class' => 'apply'
            ]
        );
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

}