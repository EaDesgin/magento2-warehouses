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

namespace Eadesigndev\Warehouses\Ui\Component\Listing\Column\Store;

use Eadesigndev\Warehouses\Helper\Validations;
use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Framework\Escaper;
use Magento\Framework\App\Request\Http;

class Options extends StoreOptions
{

    /**
     * The helper
     * @var Validations
     */
    private $validations;

    /**
     * @var
     */
    private $request;

    /**
     * All Store Views value
     */
    const ALL_STORE_VIEWS = '0';

    /**
     * Options constructor.
     * @param SystemStore $systemStore
     * @param Escaper $escaper
     * @param Validations $validations
     * @param Http $request
     */
    public function __construct(
        SystemStore $systemStore,
        Escaper $escaper,
        Validations $validations,
        Http $request
    ) {
        $this->validations = $validations;
        $this->request = $request;
        parent::__construct($systemStore, $escaper);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $this->generateCurrentOptions();

        $this->options = array_values($this->currentOptions);

        $this->options[] = [
            'label' => 'Please select',
            'value' => ''
        ];

        return $this->options;
    }

    /**
     * Generate current options
     *
     * @return void
     */
    protected function generateCurrentOptions()
    {
        $websiteCollection = $this->systemStore->getWebsiteCollection();
        $groupCollection = $this->systemStore->getGroupCollection();
        $storeCollection = $this->systemStore->getStoreCollection();
        /** @var \Magento\Store\Model\Website $website */
        foreach ($websiteCollection as $website) {
            $groups = [];
            /** @var \Magento\Store\Model\Group $group */
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() == $website->getId()) {
                    $stores = [];
                    /** @var  \Magento\Store\Model\Store $store */
                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() == $group->getId()) {
                            //$stock = $this->validations->zone($store->getId());
                            // TODO add bether filter here - ise id to load the dta or some way to get the current model
                            if ($store->getId() == \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID) {
                                continue;
                            }

                            $name = $this->escaper->escapeHtml($store->getName());
                            $stores[$name]['label'] = str_repeat(' ', 8) . $name;
                            $stores[$name]['value'] = $store->getId();
                        }
                    }
                    if (!empty($stores)) {
                        $name = $this->escaper->escapeHtml($group->getName());
                        $groups[$name]['label'] = str_repeat(' ', 4) . $name;
                        $groups[$name]['value'] = array_values($stores);
                    }
                }
            }
            if (!empty($groups)) {
                $name = $this->escaper->escapeHtml($website->getName());
                $this->currentOptions[$name]['label'] = $name;
                $this->currentOptions[$name]['value'] = array_values($groups);
            }
        }
    }
}