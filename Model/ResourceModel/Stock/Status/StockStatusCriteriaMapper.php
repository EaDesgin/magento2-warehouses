<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eadesigndev\Warehouses\Model\ResourceModel\Stock\Status;

use Magento\Framework\DB\GenericMapper;
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
class StockStatusCriteriaMapper extends GenericMapper
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
     * Apply initial query parameters
     *
     * @return void
     */
    public function mapInitialCondition()
    {
        $this->getSelect()->join(
            ['cp_table' => $this->getTable('catalog_product_entity')],
            'main_table.product_id = cp_table.entity_id',
            ['sku', 'type_id']
        );
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

    /**
     * Apply product(s) filter
     *
     * @param int|array|\Magento\Catalog\Model\Product|\Magento\Catalog\Model\Product[] $products
     * @return void
     */
    public function mapProductsFilter($products)
    {
        $productIds = [];
        if (!is_array($products)) {
            $products = [$products];
        }
        foreach ($products as $product) {
            if ($product instanceof \Magento\Catalog\Model\Product) {
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
     * Apply filter by quantity
     *
     * @param float|int $qty
     * @return void
     */
    public function mapQtyFilter($qty)
    {
        $this->addFieldToFilter('main_table.qty', ['lteq' => $qty]);
    }
}
