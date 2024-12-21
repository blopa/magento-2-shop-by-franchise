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

namespace Magiccart\Shopfranchise\Block\Widget;
// use Magento\Framework\App\Filesystem\DirectoryList;

class Franchise extends \Magiccart\Shopfranchise\Block\Franchise implements \Magento\Widget\Block\BlockInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const DEFAULT_CACHE_TAG = 'MAGICCART_FRANCHISE_WIDGET';

    protected function _construct()
    {
        $data = $this->_helper->getConfigModule('general');
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
        $keyInfo[]   =  $this->_storeManager->getStore()->getStoreId();
        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::DEFAULT_CACHE_TAG, self::DEFAULT_CACHE_TAG . '_' . $this->_storeManager->getStore()->getStoreId()];
    }

    public function getFranchises()
    {
        return $this->getFranchiseCollection();
    }

    public function getUrlFranchise($franchise)
    { 
        return $this->_helper->getLinkFranchise($franchise);
    }

}
