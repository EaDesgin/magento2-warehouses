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


class Stock extends \Magento\CatalogInventory\Model\Indexer\Stock implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var \Magento\CatalogInventory\Model\Indexer\Stock\Action\Row
     */
    protected $_productStockIndexerRow;

    /**
     * @var \Magento\CatalogInventory\Model\Indexer\Stock\Action\Rows
     */
    protected $_productStockIndexerRows;

    /**
     * @var \Magento\CatalogInventory\Model\Indexer\Stock\Action\Full
     */
    protected $_productStockIndexerFull;

    /**
     * @param Stock\Action\Row $productStockIndexerRow
     * @param Stock\Action\Rows $productStockIndexerRows
     * @param Stock\Action\Full $productStockIndexerFull
     */
    public function __construct(
        \Eadesigndev\Warehouses\Model\Indexer\Stock\Action\Row $productStockIndexerRow,
        \Eadesigndev\Warehouses\Model\Indexer\Stock\Action\Rows $productStockIndexerRows,
        \Eadesigndev\Warehouses\Model\Indexer\Stock\Action\Full $productStockIndexerFull
    ) {
        $this->_productStockIndexerRow = $productStockIndexerRow;
        $this->_productStockIndexerRows = $productStockIndexerRows;
        $this->_productStockIndexerFull = $productStockIndexerFull;
    }
}
