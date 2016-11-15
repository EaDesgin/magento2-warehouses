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
namespace Eadesigndev\Warehouses\Model\ResourceModel\Stock;

use Magento\CatalogInventory\Model\Stock;
use Magento\CatalogInventory\Api\StockConfigurationInterface;

/**
 * CatalogInventory Stock Status per website Resource Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Status extends \Magento\CatalogInventory\Model\ResourceModel\Stock\Status
{

    private $stockId = Stock::DEFAULT_STOCK_ID;

    /**
     * @var StockConfigurationInterface
     */
    private $stockConfiguration;

    /**
     * Resource model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('warehouseinventory_stock_status', 'product_id');
    }

    /**
     * Save Product Status per website
     *
     * @param int $productId
     * @param int $status
     * @param float|int $qty
     * @param int|null $websiteId
     * @param int $stockId
     * @return $this
     */
    public function saveProductStatus(
        $productId,
        $status,
        $qty,
        $websiteId,
        $stockId = Stock::DEFAULT_STOCK_ID
    )
    {

        $stockId = $this->stockId();

        //todo add the stock status if needed. Check for the status in the system. Stock::DEFAULT_STOCK_ID

        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable())
            ->where('product_id = :product_id')
            ->where('website_id = :website_id')
            ->where('stock_id = :stock_id');
        $bind = [':product_id' => $productId, ':website_id' => $websiteId, ':stock_id' => $stockId];
        $row = $connection->fetchRow($select, $bind);
        if ($row) {
            $bind = ['qty' => $qty, 'stock_status' => $status];
            $where = [
                $connection->quoteInto('product_id=?', (int)$row['product_id']),
                $connection->quoteInto('website_id=?', (int)$row['website_id']),
            ];
            $connection->update($this->getMainTable(), $bind, $where);
        } else {
            $bind = [
                'product_id' => $productId,
                'website_id' => $websiteId,
                'stock_id' => $stockId,
                'qty' => $qty,
                'stock_status' => $status,
            ];
            $connection->insert($this->getMainTable(), $bind);
        }

        return $this;
    }

    /**
     * Retrieve product status
     * Return array as key product id, value - stock status
     *
     * @param int[] $productIds
     * @param int $websiteId
     * @param int $stockId
     * @return array
     */
    public function getProductsStockStatuses($productIds, $websiteId, $stockId = Stock::DEFAULT_STOCK_ID)
    {
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['product_id', 'stock_status'])
            ->where('product_id IN(?)', $productIds)
            ->where('stock_id=?', (int)$this->stockId())
            ->where('website_id=?', (int) $websiteId);
        return $this->getConnection()->fetchPairs($select);
    }
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param bool $isFilterInStock
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function addStockDataToCollection($collection, $isFilterInStock)
    {
        $websiteId = $this->getStockConfiguration()->getDefaultScopeId();
        $joinCondition = $this->getConnection()->quoteInto(
            'e.entity_id = stock_status_index.product_id' . ' AND stock_status_index.website_id = ?',
            $websiteId
        );

        $joinCondition .= $this->getConnection()->quoteInto(
            ' AND stock_status_index.stock_id = ?',
            $this->stockId()
        );
        $method = $isFilterInStock ? 'join' : 'joinLeft';
        $collection->getSelect()->$method(
            ['stock_status_index' => $this->getMainTable()],
            $joinCondition,
            ['is_salable' => 'stock_status']
        );

        if ($isFilterInStock) {
            $collection->getSelect()->where(
                'stock_status_index.stock_status = ?',
                Stock\Status::STATUS_IN_STOCK
            );
        }
        return $collection;
    }

    /**
     * Add only is in stock products filter to product collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return $this
     */
    public function addIsInStockFilterToCollection($collection)
    {
        $websiteId = $this->getStockConfiguration()->getDefaultScopeId();
        $joinCondition = $this->getConnection()->quoteInto(
            'e.entity_id = stock_status_index.product_id' . ' AND stock_status_index.website_id = ?',
            $websiteId
        );

        $joinCondition .= $this->getConnection()->quoteInto(
            ' AND stock_status_index.stock_id = ?',
            $this->stockId()
        );

        $collection->getSelect()->join(
            ['stock_status_index' => $this->getMainTable()],
            $joinCondition,
            []
        )->where(
            'stock_status_index.stock_status=?',
            Stock\Status::STATUS_IN_STOCK
        );
        return $this;
    }
    /**
     * @return StockConfigurationInterface
     *
     * @deprecated
     */
    private function getStockConfiguration()
    {
        if ($this->stockConfiguration === null) {
            $this->stockConfiguration = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\CatalogInventory\Api\StockConfigurationInterface');
        }
        return $this->stockConfiguration;
    }

    //todo move this ti another helper for generalization
    /**
     * @return int|void
     */
    private function stockId()
    {
        $storeId = $this->_storeManager->getStore()->getId();

        if ($storeId === null) {
            return Stock::DEFAULT_STOCK_ID;
        }

        return $this->stockId = $storeId;
    }
}
