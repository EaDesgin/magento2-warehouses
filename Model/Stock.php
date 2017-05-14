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

namespace Eadesigndev\Warehouses\Model;

use Eadesigndev\Warehouses\Api\Data\ZoneInterface;

/**
 * Class Stock
 *
 */
class Stock extends \Magento\CatalogInventory\Model\Stock implements ZoneInterface
{

    const STOCK_PRIMARY = 'stock_id';
    const ZONE_PRIMARY = 'zone_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Eadesigndev\Warehouses\Model\ResourceModel\Stock');
    }

    /**
     * @return mixed
     */
    public function getZoneId()
    {
        return $this->getData(ZoneInterface::ZONE_INCREMENT_ID);
    }

    /**
     * @param $incrementId
     * @return $this
     */
    public function setZoneId($incrementId)
    {
        return $this->setData(ZoneInterface::ZONE_INCREMENT_ID, $incrementId);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getStockId()
    {
        return $this->getData(ZoneInterface::ZONE_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setStockId($id)
    {
        return $this->setData(ZoneInterface::ZONE_ID, $id);
    }

    /**
     * Get zone ID
     *
     * @return int|null
     */
    public function getWebsiteId()
    {
        return $this->getData(ZoneInterface::ZONE_WEBSITE_ID);
    }

    /**
     * Set website id ID
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(ZoneInterface::ZONE_WEBSITE_ID, $websiteId);
    }

    /**
     * Get zone name
     *
     * @return zone name
     */
    public function getZoneName()
    {
        return $this->getData(ZoneInterface::ZONE_NAME);
    }

    /**
     * Set zone name
     *
     * @param $name
     * @return $this
     */
    public function setZoneName($name)
    {
        return $this->setData(ZoneInterface::ZONE_NAME, $name);
    }
}
