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

namespace Eadesigndev\Warehouses\Api\Data;

/**
 * Zone interface.
 * @api
 */
interface ZoneInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ZONE_INCREMENT_ID = 'zone_id';
    const ZONE_ID = 'stock_id';
    const ZONE_WEBSITE_ID = 'website_id';
    const ZONE_NAME = 'stock_name';
    /**#@-*/

    /**
     * @return mixed
     */
    public function getZoneId();

    /**
     * @param $incrementId
     * @return mixed
     */
    public function setZoneId($incrementId);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getStockId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setStockId($id);

    /**
     * Get zone ID
     *
     * @return int|null
     */
    public function getWebsiteId();

    /**
     * Set website id ID
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId);

    /**
     * Get zone name
     *
     * @return zone name
     */
    public function getZoneName();

    /**
     * Set zone name
     *
     * @param $name
     * @return $this
     */
    public function setZoneName($name);
}
