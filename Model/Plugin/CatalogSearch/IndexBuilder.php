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

namespace Eadesigndev\Warehouses\Model\Plugin\CatalogSearch;


use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Indexer\ScopeResolver\IndexScopeResolver;
use Magento\CatalogSearch\Model\Search\TableMapper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Search\Adapter\Mysql\ConditionManager;
use Magento\Framework\Search\Request\Dimension;
use Magento\Framework\Search\RequestInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Store\Model\StoreManagerInterface as StoreTheFuckUp;

class IndexBuilder
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    private $indexScopeResolver;

    private $tableMapperData;

    private $configData;

    private $stockConfiguration;

    private $storeManager;

    public function __construct(
        ResourceConnection $resourceConnection,
        IndexScopeResolver $indexScopeResolver,
        TableMapper $tableMapperData,
        ScopeConfigInterface $configData,
        ConditionManager $conditionManager,
        StoreTheFuckUp $storeManager
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->indexScopeResolver = $indexScopeResolver;
        $this->tableMapperData = $tableMapperData;
        $this->configData = $configData;
        $this->conditionManager = $conditionManager;
        $this->storeManager = $storeManager;
    }

    /**
     * Build index query
     *
     * @param RequestInterface $request
     * @return Select
     */
    public function aroundBuild(\Magento\CatalogSearch\Model\Search\IndexBuilder $subject, \Closure $procede, $request)
    {

        $searchIndexTable = $this->indexScopeResolver->resolve($request->getIndex(), $request->getDimensions());
        $select = $this->resourceConnection->getConnection()->select()
            ->from(
                ['search_index' => $searchIndexTable],
                ['entity_id' => 'entity_id']
            )
            ->joinLeft(
                ['cea' => $this->resourceConnection->getTableName('catalog_eav_attribute')],
                'search_index.attribute_id = cea.attribute_id',
                []
            );

        $select = $this->tableMapperData->addTables($select, $request);

        $select = $this->processDimensions($request, $select);

        $isShowOutOfStock = $this->configData->isSetFlag(
            'cataloginventory/options/show_out_of_stock',
            ScopeInterface::SCOPE_STORE
        );
        if ($isShowOutOfStock === false) {
            $select->joinLeft(
            //todo add a contant witht the table to change, same for all the inventory tables
                ['stock_index' => $this->resourceConnection->getTableName('warehouseinventory_stock_status')],
                'search_index.entity_id = stock_index.product_id'
                . $this->resourceConnection->getConnection()->quoteInto(
                    ' AND stock_index.website_id = ?',
                    $this->getStockConfigurationData()->getDefaultScopeId()
                ),
                []
            );
            $select->where('stock_index.stock_status = ?', Stock::DEFAULT_STOCK_ID);
        }

        return $select;
    }

    /**
     * Add filtering by dimensions
     *
     * @param RequestInterface $request
     * @param Select $select
     * @return \Magento\Framework\DB\Select
     */
    private function processDimensions(RequestInterface $request, Select $select)
    {
        $dimensions = $this->prepareDimensions($request->getDimensions());

        $query = $this->conditionManager->combineQueries($dimensions, Select::SQL_OR);
        if (!empty($query)) {
            $select->where($this->conditionManager->wrapBrackets($query));
        }

        return $select;
    }

    /**
     * @param Dimension[] $dimensions
     * @return string[]
     */
    private function prepareDimensions(array $dimensions)
    {
        $preparedDimensions = [];
        foreach ($dimensions as $dimension) {
            if ('scope' === $dimension->getName()) {
                continue;
            }
            $preparedDimensions[] = $this->conditionManager->generateCondition(
                $dimension->getName(),
                '=',
                $this->dimensionScopeResolver->getScope($dimension->getValue())->getId()
            );
        }

        return $preparedDimensions;
    }

    /**
     * @return StockConfigurationInterface
     */
    private function getStockConfigurationData()
    {
        if ($this->stockConfiguration === null) {
            $this->stockConfiguration = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\CatalogInventory\Api\StockConfigurationInterface');
        }
        return $this->stockConfiguration;
    }
}
