<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eadesigndev\Warehouses\Model;

/**
 * Class Stock
 *
 */
class Stock extends \Magento\CatalogInventory\Model\Stock
{

    const WEBSITE_ID = 'website_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Eadesigndev\Warehouses\Model\ResourceModel\Stock');
    }

}
