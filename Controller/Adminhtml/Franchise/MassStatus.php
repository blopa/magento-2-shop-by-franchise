<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-22 16:59:13
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Controller\Adminhtml\Franchise;

class MassStatus extends \Magiccart\Shopfranchise\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $shopfranchiseIds = $this->getRequest()->getParam('shopfranchise');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');
        if (!is_array($shopfranchiseIds) || empty($shopfranchiseIds)) {
            $this->messageManager->addError(__('Please select Franchise(s).'));
        } else {
            $collection = $this->_shopfranchiseCollectionFactory->create()
                // ->setStoreViewId($storeViewId)
                ->addFieldToFilter('shopfranchise_id', ['in' => $shopfranchiseIds]);
            try {
                foreach ($collection as $item) {
                    $item->setStoreViewId($storeViewId)
                        ->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($shopfranchiseIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/', ['store' => $this->getRequest()->getParam('store')]);
    }
}
