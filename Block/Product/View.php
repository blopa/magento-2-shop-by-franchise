<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-07-20 10:40:51
 * @@Modify Date: 2017-07-20 10:50:03
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Block\Product;

class View extends \Magento\Framework\View\Element\Template
{

    public $_helper;

    protected $_franchise;

    /**
     * @var \Magiccart\Shopfranchise\Model\ResourceModel\Shopfranchise\CollectionFactory
     */

    protected $_franchiseCollectionFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magiccart\Shopfranchise\Model\ResourceModel\Shopfranchise\CollectionFactory $franchiseCollectionFactory,
        \Magiccart\Shopfranchise\Helper\Data $helper,
        array $data = []
        ) 
    {
        $this->_coreRegistry = $registry;
        $this->_franchiseCollectionFactory = $franchiseCollectionFactory;
        $this->_helper = $helper;

        parent::__construct($context, $data); 
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {   
        return $this->_coreRegistry->registry('current_product');
    }

    public function getFranchise()
    {
        if($this->_franchise) return $this->_franchise;

        $franchiseCode = $this->_helper->getConfigModule('general/attributeCode');
        if(!$franchiseCode) return;
        $_product = $this->getProduct();
        $_franchiseId = $_product->getData($franchiseCode);
        if(!$_franchiseId) return;
        $labelAtribute = $_product->getAttributeText($franchiseCode);

        $storeId = $this->_storeManager->getStore()->getStoreId();
        $franchise   = $this->_franchiseCollectionFactory->create()
                    ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $storeId)))
                    ->addFieldToFilter('option_id', $_franchiseId)
                    ->addFieldToFilter('status', 1)
                    ->setPageSize(1);
        $this->_franchise = $franchise->getFirstItem();
        if($this->_franchise->getId()){
            $this->_franchise->setData('label', $labelAtribute);
            return $this->_franchise;
        }
    }

    public function getUrlFranchise($franchise)
    {
        return $this->_helper->getLinkFranchise($franchise);
    }

    public function getImage($franchise)
    {
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $franchise->getImage();
        return $resizedURL;
    }

}
