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

namespace Eadesigndev\Warehouses\Api\Data;

/**
 * Item interface.
 * @api
 */
interface StockItemsInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID = 'item_id';
    const WEBSITE_ID = 'website_id';
    const ITEM_NAME = 'item_name';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getItemId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setItemId($id);

    /**
     * Get item ID
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
     * Get item name
     *
     * @return item name
     */
    public function getItemName();

    /**
     * Set item name
     *
     * @param $name
     * @return $this
     */
    public function setItemName($name);

}
