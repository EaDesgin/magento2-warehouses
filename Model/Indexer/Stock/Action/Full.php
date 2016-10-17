<?php
/**
 * @category    Magento
 * @package     Magento_CatalogInventory
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eadesigndev\Warehouses\Model\Indexer\Stock\Action;

/**
 * Class Full reindex action
 *
 * @package Magento\CatalogInventory\Model\Indexer\Stock\Action
 */
class Full extends \Eadesigndev\Warehouses\Model\Indexer\Stock\AbstractAction
{
    /**
     * Execute Full reindex
     *
     * @param null|array $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($ids = null)
    {
        try {
            $this->reindexAll();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }
}
