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

namespace Eadesigndev\Warehouses\Controller\Adminhtml\Zones;

use Eadesigndev\Warehouses\Api\Data\ZoneInterface;
use Eadesigndev\Warehouses\Controller\Adminhtml\Zones;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Exception\LocalizedException;

class Save extends Zones
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Eadesign_Zones::save';

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            $model = $this->zoneModel->getById($id);
        }

        if (!$id) {
            $model = $this->zoneFactory->create();
        }

        $data = $this->getRequest()->getParams();

        if (!$this->getRequest()->getParam('website_id')) {
            $data['website_id'] = 0;
        }
        if (!$this->getRequest()->getParam('zone_id')) {
            $data['zone_id'] = null;
        }

        $this->dataObjectHelper->populateWithArray($model, $data, ZoneInterface::class);
        $this->zoneRegistry->push($model);

        try {
            $this->zoneModel->save($model);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the zone' . $e->getMessage()));
            $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        }

        $back = $this->getRequest()->getParam('back');

        if ($back) {
            $resultRedirect->setPath('*/*/' . $back, ['id' => $model->getId()]);
            return $resultRedirect;
        }

        /** @var Page $resultPage */
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
