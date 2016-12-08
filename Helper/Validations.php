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

namespace Eadesigndev\Warehouses\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Eadesigndev\Warehouses\Model\ZoneRepository;

class Validations extends AbstractHelper
{
    /**
     * @var ZoneRepository
     */
    private $zoneRepository;

    /**
     * @var int
     */
    private $zoneId = \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID;

    /**
     * Validations constructor.
     * @param Context $context
     * @param ZoneRepository $zoneRepository
     */
    public function __construct(
        Context $context,
        ZoneRepository $zoneRepository
    )
    {
        $this->zoneRepository = $zoneRepository;
        parent::__construct($context);
    }

    /**
     * @param $zoneId
     * @return int
     */
    public function zoneById($zoneId)
    {
        if (!$zoneId) {
            return $this->zoneId;
        }

        $zoneModel = $this->zoneRepository->getById($zoneId);

        if (!$zoneModel->getId()) {
            return $this->zoneId;
        }

        $this->zoneId = $zoneId;

        return $this->zoneId;
    }

}
