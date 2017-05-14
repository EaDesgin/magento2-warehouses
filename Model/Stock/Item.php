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
namespace Eadesigndev\Warehouses\Model\Stock;

use Magento\CatalogInventory\Api\StockConfigurationInterface as StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockItemRepositoryInterface as StockItemRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\CatalogInventory\Model\Stock\Item as ModelStockItem;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Eadesigndev\Warehouses\Helper\Validations;
use Eadesigndev\Warehouses\Api\Data\StockItemsInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Catalog Inventory Stock Item Model extension
 *
 */
class Item extends ModelStockItem implements StockItemsInterface
{

    protected $validations;

    /**
     * Stock item entity code
     */
    const ENTITY = 'werehaouseinventory_stock_item';

    /**
     * Item constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param StockConfigurationInterface $stockConfiguration
     * @param StockRegistryInterface $stockRegistry
     * @param StockItemRepositoryInterface $stockItemRepository
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param Validations $validations
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        StockConfigurationInterface $stockConfiguration,
        StockRegistryInterface $stockRegistry,
        StockItemRepositoryInterface $stockItemRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Validations $validations = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $customerSession,
            $storeManager,
            $stockConfiguration,
            $stockRegistry,
            $stockItemRepository,
            $resource,
            $resourceCollection,
            $data
        );
        $this->validations = $validations;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item');
    }

    /**
     * Retrieve stock identifier
     *
     * @return int
     */
    public function getStockId()
    {

        if ($this->validations === null) {
            return Stock::DEFAULT_STOCK_ID;
        }

        $stockId = $this->validations->zoneById($this->getStoreId());

        return (int)$stockId;
    }

    /**
     * Set stock identifier
     *
     * @param int $stockId
     * @return int|$this
     */
    public function setStockId($stockId)
    {
        if ($this->validations === null) {
            return Stock::DEFAULT_STOCK_ID;
        }

        $stockId = $this->validations->zoneById($this->getStoreId());

        return $this->setData(self::STOCK_ID, $stockId);
    }
}
