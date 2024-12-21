<?php

namespace Magiccart\Shopfranchise\Block\Franchise;

use Magento\Framework\App\Filesystem\DirectoryList;

class View extends \Magiccart\Shopfranchise\Block\Franchise implements \Magento\Framework\DataObject\IdentityInterface
{

    const DEFAULT_CACHE_TAG = 'MAGICCART_FRANCHISE_VIEW';

    protected $_filterProvider;

    protected $_franchise;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        // \Magento\Framework\Filesystem $filesystem,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magiccart\Shopfranchise\Model\ShopfranchiseFactory $shopfranchiseFactory,
        \Magiccart\Shopfranchise\Helper\Data $helper,
        array $data = []

    )
    {
        $this->_filterProvider = $filterProvider;

        parent::__construct($context, $imageFactory, $backendUrl, $shopfranchiseFactory, $helper, $data);
    }

    protected function _construct()
    {
        $data = $this->_helper->getConfigModule('list_page_settings');
        //$dataConvert = array('infinite', 'vertical', 'autoplay', 'centerMode');
        if($data['slide']){
            $data['vertical-Swiping'] = $data['vertical'];
            $breakpoints = $this->getResponsiveBreakpoints();
            $responsive = '[';
            $num = count($breakpoints);
            foreach ($breakpoints as $size => $opt) {
                $item = (int) $data[$opt];
                $responsive .= '{"breakpoint": '.$size.', "settings": {"slidesToShow": '.$item.'}}';
                $num--;
                if($num) $responsive .= ', ';
            }
            $responsive .= ']';
            $data['slides-To-Show'] = $data['visible'];
            $data['autoplay-Speed'] = $data['autoplay_speed'];
            $data['swipe-To-Slide'] = 'true';
            $data['responsive'] = $responsive;
        }

        // $data['selector'] = 'alo-slider'.md5(rand());
        $this->addData($data);

        parent::_construct();

    }

    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 86400;
    }

    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $id = $this->getRequest()->getParam('id');
        $key = $this->_storeManager->getStore()->getStoreId();
        if($id)  $key = $key .'-'. $id;
        $keyInfo[]   =  $key;
        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        $id = $this->getRequest()->getParam('id');
        $key = $this->_storeManager->getStore()->getStoreId();
        if($id)  $key = $key .'-'. $id;
        return [self::DEFAULT_CACHE_TAG, self::DEFAULT_CACHE_TAG . '_' . $key];
    }

    protected function _prepareLayout()
    {
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl()
            ])->addCrumb('franchise', $this->getBreadcrumbsData());
        }

        if ($franchiseId = $this->getRequest()->getParam('id')) {
            $franchise = $this->_shopfranchiseFactory->create()->load($franchiseId);
            $title = $franchise->getData('title');
            $breadcrumbs->addCrumb($title, [
                'label' => $title,
                'title' => $title
            ]);
            $this->pageConfig->getTitle()->set(__($title));
        }
        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    protected function getBreadcrumbsData()
    {
        $data = [
            'label' => __('Franchise'),
            'title' => __('Franchise')
        ];
        $data['link'] =  $this->_helper->getFranchiseUrl();

        return $data;
    }

    public function getFranchise()
    {
        if($this->_franchise) return $this->_franchise;
        $franchiseId = $this->getRequest()->getParam('id');
        if(!$franchiseId) return;
        $this->_franchise = $this->_shopfranchiseFactory->create()->load($franchiseId);
        return $this->_franchise;
    }

    public function getDescription()
    {
        $franchise = $this->getFranchise();
        $description =  $franchise->getDescription();
        if($description){
            $storeId = $this->_storeManager->getStore()->getStoreId();
            return $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($description);
        }
    }

    /**
     * @return number
     */    
    public function getProductCount(\Magiccart\Shopfranchise\Model\Shopfranchise $franchise)
    {
        $collection = $franchise->getProductCollection();
        return $collection->count();
    }
}
