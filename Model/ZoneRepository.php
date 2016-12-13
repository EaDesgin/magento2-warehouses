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

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Stock
 *
 */
class ZoneRepository implements \Eadesigndev\Warehouses\Api\ZoneRepositoryInterface
{
    /**
     * @var ResourceModel\Stock
     */
    private $resourceModel;

    /**
     * @var array
     */
    private $zoneInstances = [];

    /**
     * @var ResourceModel\Stock
     */
    private $zoneResource;

    /**
     * @var ZoneFactory
     */
    private $zoneFactory;

    /**
     * ZoneRepository constructor.
     * @param ResourceModel\StockResourceModifier $zoneResource
     * @param ZoneFactory $zoneFactory
     * @param ResourceModel\Stock $resourceModel
     */
    public function __construct(
        \Eadesigndev\Warehouses\Model\ResourceModel\StockResourceModifier $zoneResource,
        \Eadesigndev\Warehouses\Model\ZoneFactory $zoneFactory,
        \Eadesigndev\Warehouses\Model\ResourceModel\Stock $resourceModel
    )
    {
        $this->zoneResource = $zoneResource;
        $this->zoneFactory = $zoneFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param \Eadesigndev\Warehouses\Api\Data\ZoneInterface $zone
     * @return \Eadesigndev\Warehouses\Api\Data\ZoneInterface
     * @throws CouldNotSaveException
     */
    public function save(\Eadesigndev\Warehouses\Api\Data\ZoneInterface $zone)
    {
        if ($zone->getStockId() == 1) {
            throw new CouldNotSaveException(__(
                'Could not save the zone 1. The Default zone is not editable'
            ));
        }

        $beforeSaveId = $zone->getZoneId();

        try {
            $this->zoneResource->save($zone);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the zone: %1',
                $exception->getMessage()
            ));
        }

        $afterSaveId = $zone->getZoneId();

        /** need a better system here, observer maybe */
        //todo change the validation
        if ($afterSaveId && !$beforeSaveId) {
            $this->zoneResource->insertItemsOnNewZone($zone->getStockId());
        }

        return $zone;
    }

    public function get($zoneId, $websiteId = 0)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param $zoneId
     * @return mixed
     * @throws LocalizedException
     */
    public function getByEditId($zoneId)
    {
        if (!isset($this->zoneInstances[$zoneId])) {
            $zone = $this->zoneFactory->create();

            $this->zoneResource->load($zone, $zoneId);

            if (!$zone->getId()) {
                throw new LocalizedException(__(
                    "There was a problem with the exit id."
                ));
            }

            $this->zoneInstances[$zoneId] = $zone;
        }

        return $this->zoneInstances[$zoneId];
    }

    /**
     * @param $stockId
     * @return mixed
     * @throws LocalizedException
     */
    public function getById($stockId)
    {
        if (!isset($this->zoneInstances[$stockId])) {
            $zone = $this->zoneFactory->create();

            $this->resourceModel->load($zone, $stockId);

            if (!$zone->getId()) {
                $stockId = \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID;
                $this->resourceModel->load($zone, $stockId);
            }

            if (!$zone->getId()) {
                throw new LocalizedException(__(
                    "There was a problem with the id."
                ));
            }

            $this->zoneInstances[$stockId] = $zone;
        }

        return $this->zoneInstances[$stockId];
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
