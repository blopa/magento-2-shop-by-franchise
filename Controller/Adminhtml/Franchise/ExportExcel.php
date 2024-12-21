<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-22 17:00:06
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Controller\Adminhtml\Franchise;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Magiccart\Shopfranchise\Controller\Adminhtml\Action
{
    public function execute()
    {
        $fileName = 'franchises.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Magiccart\Shopfranchise\Block\Adminhtml\Franchise\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
