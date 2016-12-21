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

namespace Eadesigndev\Warehouses\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()
            ->insertForce(
                $setup->getTable('warehouseinventory_stock'),
                ['stock_id' => 1, 'stock_name' => 'Default']
            );


        $this->copyDataFromCatalogInventoryStockItem($setup);
        $this->copyDataFromCatalogInventoryStockStatus($setup);
    }

    /**
     * copy the data from cataloginventory_stock_item to warehouseinventory_stock_item
     *
     * @param ModuleDataSetupInterface $setup
     */
    private function copyDataFromCatalogInventoryStockItem(ModuleDataSetupInterface $setup)
    {

        $select = $setup->getConnection()->select()->from(
            $setup->getConnection()->getTableName($setup->getTable('cataloginventory_stock_item')));

        $data = $setup->getConnection()->fetchAll($select);

        $setup->getConnection()
            ->insertMultiple(
                $setup->getTable('warehouseinventory_stock_item'),
                $data
            );

    }

    /**
     * copy the data from cataloginventory_stock_status to warehouseinventory_stock_status
     *
     * @param ModuleDataSetupInterface $setup
     */
    private function copyDataFromCatalogInventoryStockStatus(ModuleDataSetupInterface $setup)
    {

        $select = $setup->getConnection()->select()->from(
            $setup->getConnection()->getTableName($setup->getTable('cataloginventory_stock_status')));

        $data = $setup->getConnection()->fetchAll($select);

        $setup->getConnection()
            ->insertMultiple(
                $setup->getTable('warehouseinventory_stock_status'),
                $data
            );

    }


}
