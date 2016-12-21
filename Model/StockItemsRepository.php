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

/**
 * Class Stock
 *
 */
class StockItemsRepository implements \Eadesigndev\Warehouses\Api\StockItemsRepositoryInterface
{

    private $itemInstances = [];

    private $itemResource;

    private $itemFactory;

    public function __construct(
        \Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item $itemResource,
        \Eadesigndev\Warehouses\Model\StockItemsFactory $itemFactory
    )
    {
        $this->itemResource = $itemResource;
        $this->itemFactory = $itemFactory;
    }


    public function save(\Eadesigndev\Warehouses\Api\Data\StockItemsInterface $item)
    {

        try {
            $this->itemResource->save($item);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the zone: %1',
                $exception->getMessage()
            ));
        }

        return $item;
    }

    public function get($zoneId, $websiteId = 0)
    {
        // TODO: Implement get() method.
    }

    public function getById($itemId)
    {

        if (!isset($this->itemInstances[$itemId])) {
            $item = $this->itemFactory->create();

            $this->itemResource->load($item, $itemId);


            if (!$item->getId()) {
                //todo add exception with message here
            }

            $this->itemInstances[$itemId] = $item;
        }

        return $this->itemInstances[$itemId];
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList() method.
    }

    public function delete(\Eadesigndev\Warehouses\Api\Data\StockItemsInterface $item)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($itemId)
    {
        // TODO: Implement deleteById() method.
    }

}
