<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-02-29 14:20:06
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Block\Adminhtml\Franchise;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'shopfranchise_id';
        $this->_blockGroup = 'Magiccart_Shopfranchise';
        $this->_controller = 'adminhtml_franchise';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Franchise'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        if ($this->getRequest()->getParam('current_shopfranchise_id')) {
            $this->buttonList->remove('save');
            $this->buttonList->remove('delete');

            $this->buttonList->remove('back');
            $this->buttonList->add(
                'close_window',
                [
                    'label' => __('Close Window'),
                    'onclick' => 'window.close();',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'onclick' => 'customsaveAndContinueEdit()',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_close',
                [
                    'label' => __('Save and Close'),
                    'class' => 'save_and_close',
                    'onclick' => 'saveAndCloseWindow()',
                ],
                10
            );

            $this->_formScripts[] = "
                require(['jquery'], function($){
                    $(document).ready(function(){
                        var input = $('<input class=\"custom-button-submit\" type=\"submit\" hidden=\"true\" />');
                        $(edit_form).append(input);

                        window.customsaveAndContinueEdit = function (){
                            edit_form.action = '".$this->getSaveAndContinueUrl()."';
                            $('.custom-button-submit').trigger('click');

                        }

                        window.saveAndCloseWindow = function (){
                            edit_form.action = '".$this->getSaveAndCloseWindowUrl()."';
                            $('.custom-button-submit').trigger('click');
                        }
                    });
                });
            ";

            if ($shopfranchiseId = $this->getRequest()->getParam('shopfranchise_id')) {
                $this->_formScripts[] = '
                    window.shopfranchise_id = '.$shopfranchiseId.';
                ';
            }
        } else {
            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ],
                ],
                10
            );
        }

        if ($this->getRequest()->getParam('saveandclose')) {
            $this->_formScripts[] = 'window.close();';
        }
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'shopfranchise_id' => $this->getRequest()->getParam('shopfranchise_id'),
                'current_shopfranchise_id' => $this->getRequest()->getParam('current_shopfranchise_id'),
            ]
        );
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndCloseWindowUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'shopfranchise_id' => $this->getRequest()->getParam('shopfranchise_id'),
                'current_shopfranchise_id' => $this->getRequest()->getParam('current_shopfranchise_id'),
                'saveandclose' => 1,
            ]
        );
    }
}
