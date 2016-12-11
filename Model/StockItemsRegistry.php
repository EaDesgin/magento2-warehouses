<?php
/*******************************************************************************
 *
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
 *
 ******************************************************************************/

/**
 * EaDesgin
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
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Warehouses\Model;

use Magento\Framework\Exception\NoSuchEntityException;

class StockItemsRegistry
{

    const REGISTRY_SEPARATOR = ':';

    private $itemFactory;

    private $itemRegistryById = [];

    public function __construct(
        \Eadesigndev\Warehouses\Model\StockItemsFactory $itemFactory
    )
    {
        $this->itemFactory = $itemFactory;
    }

    public function retrieve($itemId)
    {
        if (isset($this->itemRegistryById[$itemId])) {
            return $this->itemRegistryById[$itemId];
        }
        exit('test');
        /** @var Zone $zone */
        $item = $this->itemFactory->create()->load($itemId);
        if (!$item->getId()) {
            // zone does not exist
            throw NoSuchEntityException::singleField('item_id', $itemId);
        } else {
            $emailKey = $this->getZoneKey($item->getEmail(), $item->getData('stock_name'));
            $this->itemRegistryById[$itemId] = $item;
            $this->itemRegistryByName[$emailKey] = $item;
            return $item;
        }
    }

    public function push(\Magento\CatalogInventory\Model\Stock $item)
    {
        $this->itemRegistryById[$item->getId()] = $item;
        $itemKey = $this->getZoneKey($item->getEmail(), $item->getData('stock_name'));
        $this->itemRegistryByName[$itemKey] = $item;
        return $this;
    }


    private function getZoneKey($itemId, $itemName)
    {
        return $itemId . self::REGISTRY_SEPARATOR . $itemName;
    }
}