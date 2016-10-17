<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eadesigndev\Warehouses\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'warehouseinventory_stock'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('warehouseinventory_stock'))
            ->addColumn(
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Warehouse Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'stock_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Stock Name'
            )
            ->addIndex(
                $setup->getIdxName(
                    $installer->getTable('warehouseinventory_stock'),
                    ['store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['store_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->setComment('Cataloginventory Stock');
        $installer->getConnection()
            ->createTable($table);

        /**
         * Create table 'warehouseinventory_stock_item'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('warehouseinventory_stock_item'))
            ->addColumn(
                'item_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Item Id'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Product Id'
            )
            ->addColumn(
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Warehouse Id'
            )
            ->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['unsigned' => false, 'nullable' => true, 'default' => null],
                'Qty'
            )
            ->addColumn(
                'min_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Min Qty'
            )
            ->addColumn(
                'use_config_min_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Min Qty'
            )
            ->addColumn(
                'is_qty_decimal',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Qty Decimal'
            )
            ->addColumn(
                'backorders',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Backorders'
            )
            ->addColumn(
                'use_config_backorders',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Backorders'
            )
            ->addColumn(
                'min_sale_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '1.0000'],
                'Min Sale Qty'
            )
            ->addColumn(
                'use_config_min_sale_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Min Sale Qty'
            )
            ->addColumn(
                'max_sale_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Max Sale Qty'
            )
            ->addColumn(
                'use_config_max_sale_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Max Sale Qty'
            )
            ->addColumn(
                'is_in_stock',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is In Stock'
            )
            ->addColumn(
                'low_stock_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Low Stock Date'
            )
            ->addColumn(
                'notify_stock_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                [],
                'Notify Stock Qty'
            )
            ->addColumn(
                'use_config_notify_stock_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Notify Stock Qty'
            )
            ->addColumn(
                'manage_stock',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Manage Stock'
            )
            ->addColumn(
                'use_config_manage_stock',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Manage Stock'
            )
            ->addColumn(
                'stock_status_changed_auto',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Stock Status Changed Automatically'
            )
            ->addColumn(
                'use_config_qty_increments',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Qty Increments'
            )
            ->addColumn(
                'qty_increments',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Qty Increments'
            )
            ->addColumn(
                'use_config_enable_qty_inc',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Use Config Enable Qty Increments'
            )
            ->addColumn(
                'enable_qty_increments',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Enable Qty Increments'
            )
            ->addColumn(
                'is_decimal_divided',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Divided into Multiple Boxes for Shipping'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Is Divided into Multiple Boxes for Shipping'
            )

            ->addIndex(
                $installer->getIdxName(
                    'warehouseinventory_stock_item',
                    ['item_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['item_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
            )
            ->addIndex(
                $installer->getIdxName(
                    'warehouseinventory_stock_item',
                    ['store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_item', ['stock_id']),
                ['stock_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'warehouseinventory_stock_item',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('warehouseinventory_stock_item', 'stock_id', 'warehouseinventory_stock', 'stock_id'),
                'stock_id',
                $installer->getTable('warehouseinventory_stock'),
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Cataloginventory Stock Item');
        $installer->getConnection()
            ->createTable($table);

        /**
         * Create table 'warehouseinventory_stock_status'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('warehouseinventory_stock_status'))
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Product Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website Id'
            )
            ->addColumn(
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Warehouse Id'
            )
            ->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Qty'
            )
            ->addColumn(
                'stock_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Stock Status'
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status', ['stock_id']),
                ['stock_id']
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status', ['store_id']),
                ['store_id']
            )
            ->setComment('Cataloginventory Stock Status');
        $installer->getConnection()
            ->createTable($table);

        /**
         * Create table 'warehouseinventory_stock_status_idx'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('warehouseinventory_stock_status_idx'))
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Product Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website Id'
            )
            ->addColumn(
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Warehouse Id'
            )
            ->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Qty'
            )
            ->addColumn(
                'stock_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Stock Status'
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status_idx', ['stock_id']),
                ['stock_id']
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status_idx', ['store_id']),
                ['store_id']
            )
            ->setComment('Cataloginventory Stock Status Indexer Idx');
        $installer->getConnection()
            ->createTable($table);

        /**
         * Create table 'warehouseinventory_stock_status_tmp'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('warehouseinventory_stock_status_tmp'))
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Product Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website Id'
            )
            ->addColumn(
                'stock_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Warehouse Id'
            )
            ->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Qty'
            )
            ->addColumn(
                'stock_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Stock Status'
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status_tmp', ['stock_id']),
                ['stock_id']
            )
            ->addIndex(
                $installer->getIdxName('warehouseinventory_stock_status_tmp', ['store_id']),
                ['store_id']
            )
            ->setOption(
                'type',
                \Magento\Framework\DB\Adapter\Pdo\Mysql::ENGINE_MEMORY
            )
            ->setComment('Cataloginventory Stock Status Indexer Tmp');
        $installer->getConnection()
            ->createTable($table);

        $installer->endSetup();

    }
}
