<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-03-24 17:16:17
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Block\Adminhtml\Franchise\Edit\Tab;

use Magiccart\Shopfranchise\Model\Status;
class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * @var \Magento\Catalog\Model\Category\Attribute\Source\Page
     */    
    protected $_franchise;

    /**
     * @var \Magiccart\Shopfranchise\Model\Shopfranchise
     */
    protected $_shopfranchise;

    /**
     * @var \Magiccart\Shopfranchise\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magiccart\Shopfranchise\Model\Shopfranchise $shopfranchise,
        \Magiccart\Shopfranchise\Model\System\Config\Franchise $franchise,
        \Magiccart\Shopfranchise\Helper\Data $helper,
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_shopfranchise = $shopfranchise;
        $this->_franchise   = $franchise;
        $this->_helper  = $helper;
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
        return $this;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('shopfranchise');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('magic_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Franchise Information')]);

        if ($model->getId()) {
            $fieldset->addField('shopfranchise_id', 'hidden', ['name' => 'shopfranchise_id']);
        }

        $fieldset->addField('title', 'text',
            [
                'label' => __('Title'),
                'title' => __('Title'),
                'name'  => 'title',
                'required' => true,
            ]
        );

        $fieldset->addField('urlkey', 'text',
            [
                'label' => __('URL key'),
                'title' => __('URL key'),
                'name'  => 'urlkey',
                'required' => true,
                'class' => 'validate-xml-identifier',
            ]
        );


        // $fieldset->addField('meta_key', 'text',
        //     [
        //         'label' => __('Meta Keywords'),
        //         'title' => __('Meta Keywords'),
        //         'name'  => 'meta_key',
        //         'required' => false,
        //     ]
        // );

        // $fieldset->addField('meta_description', 'text',
        //     [
        //         'label' => __('Meta Description'),
        //         'title' => __('Meta Description'),
        //         'name'  => 'meta_description',
        //         'required' => false,
        //     ]
        // );

        $franchiseOptions = $this->_franchise->toOptionArray();
        if(array_filter($franchiseOptions)){
            $fieldset->addField('option_id', 'select',
                [
                    'label' => __('Franchise'),
                    'title' => __('Franchise'),
                    'name' => 'option_id',
                    'options' => $this->_franchise->toOptionArray(),
                ]
            );
        }

        $fieldset->addField('image', 'image',
            [
                'label' => __('Franchise Logo'),
                'title' => __('Franchise Logo'),
                'name'  => 'image',
                'required' => true,
            ]
        );
        
        $fieldset->addField('description', 'editor', [
            'name'   => 'description',
            'label'  => __('Description'),
            'title'  => __('Description'),
            'config' => $this->_wysiwygConfig->getConfig([
                'add_variables'  => false,
                'add_widgets'    => true,
                'add_directives' => true
            ])
        ]);
        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField('status', 'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'options' => Status::getAvailableStatuses(),
            ]
        );

        $form->addValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getShopfranchise()
    {
        return $this->_coreRegistry->registry('shopfranchise');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getShopfranchise()->getId()
            ? __("Edit Franchise '%1'", $this->escapeHtml($this->getShopfranchise()->getTitle())) : __('New Franchise');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
