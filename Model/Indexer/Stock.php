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

namespace Eadesigndev\Warehouses\Model\Indexer;

use Magento\CatalogInventory\Model\Indexer\Stock as ModelIndexerStock;
use Magento\CatalogInventory\Model\Indexer\Stock\Action\Full;
use Magento\CatalogInventory\Model\Indexer\Stock\Action\Row;
use Magento\CatalogInventory\Model\Indexer\Stock\Action\Rows;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;

class Stock extends ModelIndexerStock implements ActionInterface, MviewActionInterface
{
    /**
     * @var Row
     */
    protected $_productStockIndexerRow;

    /**
     * @var Rows
     */
    protected $_productStockIndexerRows;

    /**
     * @var Full
     */
    protected $_productStockIndexerFull;

    /**
     * Stock constructor.
     * @param Row $productStockIndexerRow
     * @param Rows $productStockIndexerRows
     * @param Full $productStockIndexerFull
     */
    public function __construct(
        Row $productStockIndexerRow,
        Rows $productStockIndexerRows,
        Full $productStockIndexerFull
    ) {
        $this->_productStockIndexerRow = $productStockIndexerRow;
        $this->_productStockIndexerRows = $productStockIndexerRows;
        $this->_productStockIndexerFull = $productStockIndexerFull;
    }
}
