<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-22 17:00:54
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Controller\Adminhtml\Franchise;

class Delete extends \Magiccart\Shopfranchise\Controller\Adminhtml\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('shopfranchise_id');
        try {
            $item = $this->_shopfranchiseFactory->create()->setId($id);
            $item->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
