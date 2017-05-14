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

namespace Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DB\GenericMapper;
use Magento\Framework\DB\MapperFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Data\ObjectFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Eadesigndev\Warehouses\Helper\Validations;

/**
 * Interface StockItemCriteriaMapper
 * @package Magento\CatalogInventory\Model\ResourceModel\Stock\Status
 */
class StockItemCriteriaMapper extends GenericMapper
{
    /**
     * @var StockConfigurationInterface
     */
    private $stockConfiguration;

    /**
     * @var StoreManagerInterface
     * @deprecated
     */
    private $storeManager;

    /**
     * StockItemCriteriaMapper constructor.
     * @param Logger $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ObjectFactory $objectFactory
     * @param MapperFactory $mapperFactory
     * @param StoreManagerInterface $storeManager
     * @param Validations $validations
     * @param Select|null $select
     */
    public function __construct(
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        ObjectFactory $objectFactory,
        MapperFactory $mapperFactory,
        StoreManagerInterface $storeManager,
        Validations $validations,
        Select $select = null
    ) {
        $this->validations = $validations;
        $this->storeManager = $storeManager;
        parent::__construct($logger, $fetchStrategy, $objectFactory, $mapperFactory, $select);
    }

    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->initResource('Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item');
        $this->map['qty'] = ['main_table', 'qty', 'qty'];
        $this->mapStockFilter();
    }

    /**
     * @inheritdoc
     */
    public function mapInitialCondition()
    {
        $this->getSelect()->join(
            ['cp_table' => $this->getTable('catalog_product_entity')],
            'main_table.product_id = cp_table.entity_id',
            ['type_id']
        );
    }

    /**
     * @inheritdoc
     */
    public function mapStockFilter()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $stockId = $this->validations->zoneById($storeId);

        $this->addFieldToFilter('main_table.stock_id', $stockId);
    }

    /**
     * @inheritdoc
     */
    public function mapWebsiteFilter($website)
    {
        if ($website instanceof \Magento\Store\Model\Website) {
            $website = $website->getId();
        }
        $this->addFieldToFilter('main_table.website_id', $website);
    }

    /**
     * @inheritdoc
     */
    public function mapProductsFilter($products)
    {
        $productIds = [];
        if (!is_array($products)) {
            $products = [$products];
        }
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $productIds[] = $product->getId();
            } else {
                $productIds[] = $product;
            }
        }
        if (empty($productIds)) {
            $productIds[] = false;
        }
        $this->addFieldToFilter('main_table.product_id', ['in' => $productIds]);
    }

    /**
     * @inheritdoc
     */
    public function mapStockStatus($storeId = null)
    {
        $websiteId = $this->getStockConfiguration()->getDefaultScopeId();
        $this->getSelect()->joinLeft(
            ['status_table' => $this->getTable('warehouseinventory_stock_status')],
            'main_table.product_id=status_table.product_id' .
            ' AND main_table.stock_id=status_table.stock_id' .
            $this->connection->quoteInto(
                ' AND status_table.store_id=?',
                $websiteId
            ),
            ['stock_status']
        );
    }

    /**
     * @inheritdoc
     */
    public function mapManagedFilter($isStockManagedInConfig)
    {
        if ($isStockManagedInConfig) {
            $this->getSelect()->where('(manage_stock = 1 OR use_config_manage_stock = 1)');
        } else {
            $this->addFieldToFilter('manage_stock', 1);
        }
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function mapQtyFilter($comparisonMethod, $qty)
    {
        $methods = ['<' => 'lt', '>' => 'gt', '=' => 'eq', '<=' => 'lteq', '>=' => 'gteq', '<>' => 'neq'];
        if (!isset($methods[$comparisonMethod])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('%1 is not a correct comparison method.', $comparisonMethod)
            );
        }
        $this->addFieldToFilter('main_table.qty', [$methods[$comparisonMethod] => $qty]);
    }

    /**
     * @return StockConfigurationInterface
     *
     * @deprecated
     */
    private function getStockConfiguration()
    {
        if ($this->stockConfiguration === null) {
            $this->stockConfiguration = ObjectManager::getInstance()
                ->get('Magento\CatalogInventory\Api\StockConfigurationInterface');
        }
        return $this->stockConfiguration;
    }
}
