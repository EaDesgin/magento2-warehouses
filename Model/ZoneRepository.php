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


use Eadesigndev\Warehouses\Model\ResourceModel\Stock;

/**
 * Class Stock
 *
 */
class ZoneRepository implements \Eadesigndev\Warehouses\Api\ZoneRepositoryInterface
{

    private $zoneInstances = [];

    private $zoneResource;

    private $zoneFactory;

    public function __construct(
        \Eadesigndev\Warehouses\Model\ResourceModel\Stock $zoneResource,
        \Eadesigndev\Warehouses\Model\ZoneFactory $zoneFactory
    )
    {
        $this->zoneResource = $zoneResource;
        $this->zoneFactory = $zoneFactory;
    }


    public function save(\Eadesigndev\Warehouses\Api\Data\ZoneInterface $zone)
    {
        // TODO: Implement save() method.
    }

    public function get($storeId, $websiteId = null)
    {
        // TODO: Implement get() method.
    }

    public function getById($zoneId)
    {
        if (!isset($this->zoneInstances[$zoneId])) {
            $zone = $this->zoneFactory->create();

            $this->zoneResource->load($zone, $zoneId);

            if (!$zone->getId()) {
                //todo add exception with message here
            }

            $this->zoneInstances[$zoneId] = $zone;
        }

        return $this->zoneInstances[$zoneId];
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList() method.
    }

    public function delete(\Eadesigndev\Warehouses\Api\Data\ZoneInterface $zone)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($zoneId)
    {
        // TODO: Implement deleteById() method.
    }

}
