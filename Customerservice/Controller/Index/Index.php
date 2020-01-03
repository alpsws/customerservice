<?php
 
namespace Alps\Customerservice\Controller\Index;
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $pageConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Page\Config $pageConfig
    )
    {
        $this->_pageConfig = $pageConfig;
        parent::__construct($context);
    }

   
    public function execute()
    {
        $this->_view->loadLayout();       
        $this->_pageConfig->getTitle()->set(__('Customer Service'));
        $this->_view->renderLayout();
    }
}