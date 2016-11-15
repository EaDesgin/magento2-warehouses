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

namespace Eadesigndev\Warehouses\Model\ResourceModel\Stock\Status;

use Magento\Framework\DB\MapperFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Data\ObjectFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;


/**
 * Class StockStatusCriteriaMapper
 * @package Magento\CatalogInventory\Model\ResourceModel\Stock\Status
 */
class StockStatusCriteriaMapper extends \Magento\CatalogInventory\Model\ResourceModel\Stock\Status\StockStatusCriteriaMapper
{
    /**
     * @var StoreManagerInterface
     * @deprecated
     */
    private $storeManager;

    public function __construct(
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        ObjectFactory $objectFactory,
        MapperFactory $mapperFactory,
        StoreManagerInterface $storeManager,
        Select $select = null
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($logger, $fetchStrategy, $objectFactory, $mapperFactory, $select);
    }

    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->initResource('Eadesigndev\Warehouses\Model\ResourceModel\Stock\Status');
        $this->mapStockFilter();
    }

    /**
     * @inheritdoc
     */
    public function mapStockFilter()
    {
        $storeId = $this->storeManager->getStore()->getId();

        if ($storeId == 0) {
            $storeId = 1;
        }

        $stock = $storeId;
        $this->addFieldToFilter('main_table.stock_id', $stock);
    }

    /**
     * Apply website filter
     *
     * @param int|\Magento\Store\Model\Website $website
     * @return void
     */
    public function mapWebsiteFilter($website)
    {
        if ($website instanceof \Magento\Store\Model\Website) {
            $website = $store->getId();
        }
        $this->addFieldToFilter('main_table.website_id', $website);
    }
}
