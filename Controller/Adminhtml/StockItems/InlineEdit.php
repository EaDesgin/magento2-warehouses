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

namespace Eadesigndev\Warehouses\Controller\Adminhtml\StockItems;

use Magento\Framework\Controller\Result\JsonFactory;
use Eadesigndev\Warehouses\Api\StockItemsRepositoryInterface;

class InlineEdit extends \Eadesigndev\Warehouses\Controller\Adminhtml\StockItems
{

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Eadesigndev\Warehouses\Model\StockItemsRepository $itemModel
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Eadesigndev\Warehouses\Model\StockItemsRepository $itemModel,
        JsonFactory $jsonFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context, $resultPageFactory, $itemModel);
    }


    /**
     * @return $this
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $itemsId) {

                    $items = $this->itemModel->getById($itemsId);

                    try {
                        $items->setData(array_merge($items->getData(), $postItems[$itemsId]));
                        $this->itemModel->save($items);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithItemId(
                            $items,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * @param StockItemsRepositoryInterface $item
     * @param $errorText
     * @return string
     */
    protected function getErrorWithItemId(StockItemsRepositoryInterface $item, $errorText)
    {
        return '[Block ID: ' . $item->getId() . '] ' . $errorText;
    }
}
