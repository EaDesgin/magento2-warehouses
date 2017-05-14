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

use Eadesigndev\Warehouses\Api\Data\StockItemsInterface;
use Eadesigndev\Warehouses\Api\StockItemsRepositoryInterface;
use Eadesigndev\Warehouses\Model\ResourceModel\Stock\Item;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class Stock
 */
class StockItemsRepository implements StockItemsRepositoryInterface
{

    /**
     * @var array
     */
    private $itemInstances = [];

    /**
     * @var Item
     */
    private $itemResource;

    /**
     * @var StockItemsFactory
     */
    private $itemFactory;

    /**
     * StockItemsRepository constructor.
     * @param Item $itemResource
     * @param StockItemsFactory $itemFactory
     */
    public function __construct(
        Item $itemResource,
        StockItemsFactory $itemFactory
    ) {
        $this->itemResource = $itemResource;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param StockItemsInterface $item
     * @return StockItemsInterface
     * @throws CouldNotSaveException
     */
    public function save(StockItemsInterface $item)
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

    public function getById($itemId)
    {
        if (!isset($this->itemInstances[$itemId])) {
            $item = $this->itemFactory->create();
            $this->itemResource->load($item, $itemId);
            $this->itemInstances[$itemId] = $item;
        }

        return $this->itemInstances[$itemId];
    }
}
