<?php
/**
 * Magiccart 
 * @category     Magiccart 
 * @copyright     Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-03-04 11:44:03
 * @@Modify Date: 2016-03-24 17:26:49
 * @@Function:
 */
namespace Magiccart\Shopfranchise\Block\Adminhtml\Helper\Renderer\Grid;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Shopfranchise factory.
     *
     * @var \Magestore\Shopfranchise\Model\ShopfranchiseFactory
     */
    protected $_shopfranchiseFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magiccart\Shopfranchise\Model\ShopfranchiseFactory $shopfranchiseFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_shopfranchiseFactory  = $shopfranchiseFactory;
    }

    /**
     * Render action.
     *
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $storeViewId = $this->getRequest()->getParam('store');
        $franchise = $this->_shopfranchiseFactory->create()->setStoreViewId($storeViewId)->load($row->getId());
        $srcImage = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $franchise->getImage();

        return '<image width="150" height="50" src ="'.$srcImage.'" alt="'.$franchise->getImage().'" >';
    }
}
