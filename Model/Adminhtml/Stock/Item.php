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
namespace Eadesigndev\Warehouses\Model\Adminhtml\Stock;

use Eadesigndev\Warehouses\Model\Stock\Item as ModelStockItem;
use Magento\CatalogInventory\Api\StockConfigurationInterface as StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockItemRepositoryInterface as StockItemRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Catalog\Model\Product;
use Eadesigndev\Warehouses\Helper\Validations;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Catalog Inventory Stock Model for adminhtml area
 * @method \Magento\CatalogInventory\Api\Data\StockItemExtensionInterface getExtensionAttributes()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Item extends ModelStockItem implements IdentityInterface
{
    /**
     * @var GroupManagementInterface
     */
    protected $groupManagement;

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
     * @param GroupManagementInterface $groupManagement
     * @param Validations $validations
     * @param AbstractDb|null $resourceCollection
     * @param AbstractResource|null $resource
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
        GroupManagementInterface $groupManagement,
        Validations $validations,
        AbstractDb $resourceCollection = null,
        AbstractResource $resource = null,
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
            $validations,
            $data
        );

        $this->groupManagement = $groupManagement;
    }

    /**
     * Getter for customer group id, return default group if not set
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        if ($this->customerGroupId === null) {
            return $this->groupManagement->getAllCustomersGroup()->getId();
        }
        return parent::getCustomerGroupId();
    }

    /**
     * Check if qty check can be skipped. Skip checking in adminhtml area
     *
     * @return bool
     */
    protected function _isQtyCheckApplicable()
    {
        return true;
    }

    /**
     * Check if notification message should be added despite of backorders notification flag
     *
     * @return bool
     */
    protected function _hasDefaultNotificationMessage()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasAdminArea()
    {
        return true;
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getShowDefaultNotificationMessage()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getIdentities()
    {
        $tags = [];
        if ($this->getProductId()) {
            $tags[] = Product::CACHE_TAG . '_' . $this->getProductId();
        }

        return $tags;
    }
}
