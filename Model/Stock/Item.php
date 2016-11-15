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
namespace Eadesigndev\Warehouses\Model\Stock;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

/**
 * Catalog Inventory Stock Item Model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Item extends \Magento\CatalogInventory\Model\Stock\Item implements StockItemInterface
{
    /**
     * Stock item entity code
     */
    const ENTITY = 'werehaouseinventory_stock_item';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item');
    }
    /**
     * Retrieve stock identifier
     *
     * @return int
     */
    public function getStockId()
    {
//        $stockId = $this->getData(static::STOCK_ID);
//        if ($stockId === null) {
//            $stockId = $this->stockRegistry->getStock($this->getWebsiteId())->getStockId();
//        }

        //todo ned to validate here for store 0

        $stockId = $this->getStoreId();

        if ($this->getStoreId() == 0) {
            $stockId = 1;
        }

        return (int)$stockId;
    }

    /**
     * Set stock identifier
     *
     * @param int $stockId
     * @return $this
     */
    public function setStockId($stockId)
    {
        $stockId = $this->getStoreId();

        //todo validate here with the same system as get

        if ($this->getStoreId() == 0) {
            $stockId = 1;
        }

        return $this->setData(self::STOCK_ID, $stockId);
    }
}
