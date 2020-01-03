<?php

namespace Alps\Customerservice\Block\Adminhtml\Customerservice\Edit;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context,
     * @param \Magento\Framework\Registry $registry,
     * @param \Magento\Framework\Data\FormFactory $formFactory,
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
     * @param \Webkul\Grid\Model\Status $options,
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Alps\Customerservice\Model\Status $options,        
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_options = $options;        
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {          
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );
        
        if ($model->getCustomerServiceId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('customer_service_id', 'hidden', ['name' => 'customer_service_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Row Data'), 'class' => 'fieldset-wide']
            );
        }

         $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'id' => 'status',
                'title' => __('Status'),
                'values' => $this->_options->getOptionArray(),
                'class' => 'status',
                'required' => true,
            ]
        );
         
        $fieldset->addField(
           'store_id',
           'multiselect',
           [
             'name'     => 'store_id[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
           ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'id' => 'title',
                'title' => __('title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

         $fieldset->addField(
            'url',
            'text',
            array(
                'name' => 'url',
                'id' => 'url',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
                'class' => 'required-entry',
                'required' => true,
            )
        );

         $fieldset->addField(
            'sort_number',
            'text',
            [
                'name' => 'sort_number',
                'label' => __('Sort Order'),
                'id' => 'sort_number',
                'title' => __('sort_number'),                
                'required' => false,
            ]
        );

        $fieldset->addField(
            'icon',
            'image',
            array(
                'name' => 'icon',
                'label' => __('Black Icon'),
                'title' => __('Icon')
            )
        );

        $fieldset->addField(
            'hover_icon',
            'image',
            array(
                'name' => 'hover_icon',
                'label' => __('Hover Icon'),
                'title' => __('hover_icon')
            )
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'style' => 'height:36em;',
                'required' => true,
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField(
            'custom_css',
            'editor',
            array(
                'name' => 'custom_css',
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS')
            )
        );


        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
