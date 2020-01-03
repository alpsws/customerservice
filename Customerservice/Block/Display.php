<?php
namespace Alps\Customerservice\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Alps\Customerservice\Model\ResourceModel\Customerservice\CollectionFactory as CustomerCollectionFactory ;
use Alps\Customerservice\Model\CustomerserviceFactory;

class Display extends Template
{   
    
	public function __construct
        (   Context $context,           
            CustomerCollectionFactory $customerCollectionFactory,
            StoreManagerInterface $storeManager,
            CustomerserviceFactory $customerserviceFactory
        )
	{	        
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->_storeManager = $storeManager;		
        $this->_customerserviceFactory = $customerserviceFactory;
		parent::__construct($context);
	}

	public function getCustomerserviceCollection()
    {
    	$dataModel = $this->_customerCollectionFactory->create()
        ->addFieldToFilter('status', ['eq' => 1])
        ->setOrder('sort_number', 'ASC')
        ->addStoreFilter($this->_storeManager->getStore()->getId());
        return $dataModel;
    }   

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {              
            $customerserviceurl = $this->getCustomerService() ? 'customerservice' : '';
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Home'),
                'link'  => $this->getUrl()
            ]);
            $breadcrumbs->addCrumb('customerservice', [
                'label' => __('Customer service'),
                'title' => __('Customer service'),
                'link'  => $customerserviceurl
            ]);
            if ($this->getCustomerService()) {
                $service = $this->getCustomerService();
                $this->pageConfig->getTitle()->set($service->getTitle());
                $breadcrumbs->addCrumb($service->getUrl(), [
                    'label' => $service->getTitle(),
                    'title' => $service->getTitle(),
                    'link'  => ''
                    
                ]);
            }            
        }
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getCustomerServiceViewUrl($url_key)
    {        
        return $this->getUrl().$url_key;        
    }

    public function getItemIcon($icon)
    {
        return $this->getMediaUrl().$icon;
    }

    public function getBackButtonUrl()
    {
        return $this->getUrl().'customerservice';
    }

    public function getItemHoverIcon($hovericon)
    {
        return $this->getMediaUrl().$hovericon;
    }

    public function getCustomerService()
    {   
        if($this->getRequest()->getParam('customer_service_id')){
            $id = $this->getRequest()->getParam('customer_service_id');
            $service = $this->_customerserviceFactory->create()->load($id);
            return $service;
        }
        return false;        
    }
}
