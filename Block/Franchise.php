<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-09-30 17:38:38
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Block;

use Magiccart\Shopfranchise\Model\Design\Frontend\Responsive;

class Franchise extends \Magento\Framework\View\Element\Template
{
    /**
    * @var \Magiccart\Shopfranchise\Helper\Data
    */
    public $_helper;

    protected $_imageFactory;
    // protected $_filesystem;
    // protected $_directory;

    protected $_franchises = [];

    /**
    * @var \Magiccart\Shopfranchise\Model\ShopfranchiseFactory
    */
    protected $_shopfranchiseFactory;

    protected $_attribute = [];

    /**
    * @var \Magento\Backend\Model\UrlInterface
    */
    protected $backendUrl;

    /**
     * @var \Magiccart\Shopfranchise\Model\ResourceModel\Shopfranchise\Collection
     */
    protected $_franchiseCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        // \Magento\Framework\Filesystem $filesystem,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magiccart\Shopfranchise\Model\ShopfranchiseFactory $shopfranchiseFactory,
        \Magiccart\Shopfranchise\Helper\Data $helper,
        array $data = []
    ) {
        $this->_imageFactory = $imageFactory;
        // $this->_filesystem = $filesystem;
        // $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->backendUrl = $backendUrl;
        $this->_shopfranchiseFactory = $shopfranchiseFactory;
        $this->_helper = $helper;

        parent::__construct($context, $data);
    }

    public function getAdminUrl($adminPath, $routeParams=[], $storeCode = 'default' ) 
    {
        $routeParams[] = [ '_nosid' => true, '_query' => ['___store' => $storeCode]];
        return $this->backendUrl->getUrl($adminPath, $routeParams);
    }

    public function getQuickedit()
    {
        $franchises = $this->getFranchises();
        if($franchises){
            $routeParams = [
                // 'shopfranchise_id' => $id
            ];
            $class      = 'Franchise'; //basename(__FILE__, ".php");
            $adminPath  = 'shopfranchise/' . strtolower($class) . '/index';
            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
            $configUrl  = $this->getAdminUrl('adminhtml/system_config/edit/section/shopfranchise');
            $moduleName = $this->getModuleName();
            $moduleName = str_replace('_', ' > ', (string) $moduleName);
            $quickedit  = [
                [
                    'title' => __('%1 > %2 :', $moduleName, $class),
                    'url'   => $editUrl
                ],
                [
                    'title' => __('System > Stores > Configuration > Magiccart > Shop Franchise'),
                    'url'   => $configUrl
                ],
                [
                    'title' => __('Edit'),
                    'url'   => $editUrl
                ]
            ];
        }

        return $quickedit;      
    }

    public function getFranchiseCollection()
    {
        if(!$this->_franchiseCollection){
            $store = $this->_storeManager->getStore()->getStoreId();
            $collection = $this->_shopfranchiseFactory->create()->getCollection()
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
            $this->_franchiseCollection = $collection;
        }
        return $this->_franchiseCollection;
    }

    public function getImage($franchise)
    {       
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $franchise->getImage();
        return $resizedURL;
    }

    public function getResponsiveBreakpoints()
    {
        return Responsive::getBreakpoints();
        return array(1921=>'visible', 1920=>'desktop', 1200=>'laptop', 992=>'notebook', 768=>'tablet', 640=>'landscape', 480=>'portrait', 361=>'mobile', 1=>'mobile');
    }

    public function getSlideOptions()
    {
        return array('autoplay', 'arrows', 'autoplay-Speed', 'dots', 'infinite', 'padding', 'vertical', 'vertical-Swiping', 'responsive', 'rows', 'slides-To-Show', 'swipe-To-Slide');
    }

    public function getFrontendCfg()
    { 
        if($this->getSlide()) return $this->getSlideOptions();

        $this->addData(array('responsive' =>json_encode($this->getGridOptions())));
        
        return array('padding', 'responsive');

    }

    public function getGridOptions()
    {
        $options = array();
        $breakpoints = $this->getResponsiveBreakpoints(); ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size-1 => $this->getData($screen));
        }
        return $options;
    }

}
