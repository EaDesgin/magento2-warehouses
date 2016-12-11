<?php
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

class ZoneRegistry
{

    const REGISTRY_SEPARATOR = ':';

    private $zoneFactory;

    private $zoneRegistryById = [];

    public function __construct(
        \Eadesigndev\Warehouses\Model\ZoneFactory $zoneFactory
    )
    {
        $this->zoneFactory = $zoneFactory;
    }

    public function retrieve($zoneId)
    {
        if (isset($this->zoneRegistryById[$zoneId])) {
            return $this->zoneRegistryById[$zoneId];
        }

        /** @var Zone $zone */
        $zone = $this->zoneFactory->create()->load($zoneId);
        if (!$zone->getId()) {
            // zone does not exist
            throw NoSuchEntityException::singleField('zoneid', $zoneId);
        } else {
            $emailKey = $this->getZoneKey($zone->getEmail(), $zone->getData('stock_name'));
            $this->zoneRegistryById[$zoneId] = $zone;
            $this->zoneRegistryByName[$emailKey] = $zone;
            return $zone;
        }
    }

    public function push(\Magento\CatalogInventory\Model\Stock $zone)
    {
        $this->zoneRegistryById[$zone->getId()] = $zone;
        $zoneKey = $this->getZoneKey($zone->getEmail(), $zone->getData('stock_name'));
        $this->zoneRegistryByName[$zoneKey] = $zone;
        return $this;
    }


    private function getZoneKey($zoneId, $zoneName)
    {
        return $zoneId . self::REGISTRY_SEPARATOR . $zoneName;
    }
}