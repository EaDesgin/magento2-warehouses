<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eadesigndev\Warehouses\Model\ResourceModel\Stock\Status;

use Magento\Framework\DB\GenericMapper;

/**
 * Class StockStatusCriteriaMapper
 * @package Magento\CatalogInventory\Model\ResourceModel\Stock\Status
 */
class StockStatusCriteriaMapper extends GenericMapper
{
    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->initResource('Eadesigndev\Warehouses\Model\ResourceModel\Stock\Status');
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
     * Apply website filter
     *
     * @param int|\Magento\Store\Model\Website $website
     * @return void
     */
    public function mapWebsiteFilter($store)
    {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = $store->getId();
        }
        $this->addFieldToFilter('main_table.store_id', $store);
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
