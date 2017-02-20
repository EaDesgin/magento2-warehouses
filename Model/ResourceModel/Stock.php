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

namespace Eadesigndev\Warehouses\Model\ResourceModel;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Stock resource model
 */
class Stock extends \Magento\CatalogInventory\Model\ResourceModel\Stock
{


    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('warehouseinventory_stock', \Eadesigndev\Warehouses\Model\Stock::STOCK_PRIMARY);
    }

    /**
     * Insert new stock item on new zone.
     *
     * @param $zoneId
     */
    public function insertItemsOnNewZone($zoneId)
    {

        $itemTable = $this->getTable('warehouseinventory_stock_item');
        $select = $this->getConnection()->select()->from(
            $this->getConnection()->getTableName($itemTable)
        );

        // todo add here base on to me able to move stocks across zones
        $select->where('stock_id=?', \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID);

        $all = $this->getConnection()->fetchAll($select);
        $forInsert = $this->processNewZoneData($zoneId, $all);
        
        if(empty($forInsert)) {
            return;
        }
        
        $this->getConnection()
            ->insertMultiple(
                $itemTable,
                $forInsert
            );
    }

    /**
     * Reset the zone id and change the stock id for the data to be save
     *
     * @param $zoneId
     * @param array $data
     * @return array
     */
    private function processNewZoneData($zoneId, $data = [])
    {

        $final = [];
        if (!$zoneId) {
            return $final;
        }

        foreach ($data as $row) {
            $row['item_id'] = null;
            $row['stock_id'] = $zoneId;
            $final[] = $row;
        }

        return $final;
    }

    /**
     * Lock Stock Item records
     *
     * @param int[] $productIds
     * @param int $websiteId
     * @return array
     */
    public function lockProductsStock($productIds, $websiteId)
    {
        if (empty($productIds)) {
            return [];
        }
        $itemTable = $this->getTable('warehouseinventory_stock_item');
        $productTable = $this->getTable('catalog_product_entity');
        $select = $this->getConnection()->select()->from(['si' => $itemTable])
            ->join(['p' => $productTable], 'p.entity_id=si.product_id', ['type_id'])
            ->where('website_id=?', $websiteId)
            ->where('product_id IN(?)', $productIds)
            ->forUpdate(true);
        return $this->getConnection()->fetchAll($select);
    }

    /**
     * {@inheritdoc}
     */
    public function correctItemsQty(array $items, $websiteId, $operator)
    {
        if (empty($items)) {
            return $this;
        }

        $connection = $this->getConnection();
        $conditions = [];
        foreach ($items as $productId => $qty) {
            $case = $connection->quoteInto('?', $productId);
            $result = $connection->quoteInto("qty{$operator}?", $qty);
            $conditions[$case] = $result;
        }

        $value = $connection->getCaseSql('product_id', $conditions, 'qty');
        $where = ['product_id IN (?)' => array_keys($items), 'website_id = ?' => $websiteId];

        $connection->beginTransaction();
        $connection->update($this->getTable('warehouseinventory_stock_item'), ['qty' => $value], $where);
        $connection->commit();
    }

    /**
     * Set items out of stock basing on their quantities and config settings
     *
     * @param string|int $website
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function updateSetOutOfStock($website = null)
    {
        $websiteId = $this->stockConfiguration->getDefaultScopeId();
        $this->_initConfig();
        $connection = $this->getConnection();
        $values = ['is_in_stock' => 0, 'stock_status_changed_auto' => 1];

        $select = $connection->select()->from($this->getTable('catalog_product_entity'), 'entity_id')
            ->where('type_id IN(?)', $this->_configTypeIds);

        $where = sprintf(
            'website_id = %1$d' .
            ' AND is_in_stock = 1' .
            ' AND ((use_config_manage_stock = 1 AND 1 = %2$d) OR (use_config_manage_stock = 0 AND manage_stock = 1))' .
            ' AND ((use_config_backorders = 1 AND %3$d = %4$d) OR (use_config_backorders = 0 AND backorders = %3$d))' .
            ' AND ((use_config_min_qty = 1 AND qty <= %5$d) OR (use_config_min_qty = 0 AND qty <= min_qty))' .
            ' AND product_id IN (%6$s)',
            $websiteId,
            $this->_isConfigManageStock,
            \Magento\CatalogInventory\Model\Stock::BACKORDERS_NO,
            $this->_isConfigBackorders,
            $this->_configMinQty,
            $select->assemble()
        );

        $connection->update($this->getTable('warehouseinventory_stock_item'), $values, $where);
    }

    /**
     * Set items in stock basing on their quantities and config settings
     *
     * @param int|string $website
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function updateSetInStock($website)
    {
        $websiteId = $this->stockConfiguration->getDefaultScopeId();
        $this->_initConfig();
        $connection = $this->getConnection();
        $values = ['is_in_stock' => 1];

        $select = $connection->select()->from($this->getTable('catalog_product_entity'), 'entity_id')
            ->where('type_id IN(?)', $this->_configTypeIds);

        $where = sprintf(
            'website_id = %1$d' .
            ' AND is_in_stock = 0' .
            ' AND stock_status_changed_auto = 1' .
            ' AND ((use_config_manage_stock = 1 AND 1 = %2$d) OR (use_config_manage_stock = 0 AND manage_stock = 1))' .
            ' AND ((use_config_min_qty = 1 AND qty > %3$d) OR (use_config_min_qty = 0 AND qty > min_qty))' .
            ' AND product_id IN (%4$s)',
            $websiteId,
            $this->_isConfigManageStock,
            $this->_configMinQty,
            $select->assemble()
        );

        $connection->update($this->getTable('warehouseinventory_stock_item'), $values, $where);
    }

    /**
     * Update items low stock date basing on their quantities and config settings
     *
     * @param int|string $website
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function updateLowStockDate($website)
    {
        $websiteId = $this->stockConfiguration->getDefaultScopeId();
        $this->_initConfig();

        $connection = $this->getConnection();
        $condition = $connection->quoteInto(
                '(use_config_notify_stock_qty = 1 AND qty < ?)',
                $this->_configNotifyStockQty
            ) . ' OR (use_config_notify_stock_qty = 0 AND qty < notify_stock_qty)';
        $currentDbTime = $connection->quoteInto('?', $this->dateTime->gmtDate());
        $conditionalDate = $connection->getCheckSql($condition, $currentDbTime, 'NULL');

        $value = ['low_stock_date' => new \Zend_Db_Expr($conditionalDate)];

        $select = $connection->select()->from($this->getTable('catalog_product_entity'), 'entity_id')
            ->where('type_id IN(?)', $this->_configTypeIds);

        $where = sprintf(
            'store_id = %1$d' .
            ' AND ((use_config_manage_stock = 1 AND 1 = %2$d) OR (use_config_manage_stock = 0 AND manage_stock = 1))' .
            ' AND product_id IN (%3$s)',
            $websiteId,
            $this->_isConfigManageStock,
            $select->assemble()
        );

        $connection->update($this->getTable('warehouseinventory_stock_item'), $value, $where);
    }

    /**
     * Add low stock filter to product collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param array $fields
     * @return $this
     */
    public function addLowStockFilter(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection, $fields)
    {
        $this->_initConfig();
        $connection = $collection->getSelect()->getConnection();
        $qtyIf = $connection->getCheckSql(
            'invtr.use_config_notify_stock_qty > 0',
            $this->_configNotifyStockQty,
            'invtr.notify_stock_qty'
        );
        $conditions = [
            [
                $connection->prepareSqlCondition('invtr.use_config_manage_stock', 1),
                $connection->prepareSqlCondition($this->_isConfigManageStock, 1),
                $connection->prepareSqlCondition('invtr.qty', ['lt' => $qtyIf]),
            ],
            [
                $connection->prepareSqlCondition('invtr.use_config_manage_stock', 0),
                $connection->prepareSqlCondition('invtr.manage_stock', 1)
            ],
        ];

        $where = [];
        foreach ($conditions as $k => $part) {
            $where[$k] = join(' ' . \Magento\Framework\DB\Select::SQL_AND . ' ', $part);
        }

        $where = $connection->prepareSqlCondition(
                'invtr.low_stock_date',
                ['notnull' => true]
            ) . ' ' . \Magento\Framework\DB\Select::SQL_AND . ' ((' . join(
                ') ' . \Magento\Framework\DB\Select::SQL_OR . ' (',
                $where
            ) . '))';

        $collection->joinTable(
            ['invtr' => 'warehouseinventory_stock_item'],
            'product_id = entity_id',
            $fields,
            $where
        );
        return $this;
    }
}
