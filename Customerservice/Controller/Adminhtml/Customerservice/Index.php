<?php
namespace Alps\Customerservice\Controller\Adminhtml\Customerservice;

class Index extends \Magento\Backend\App\Action
{
  
    private $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**     
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {   
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Alps_Customerservice::customerservice');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Service List'));
        return $resultPage;
    }

    /**
     * Check Customer Service Manage Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Alps_Customerservice::customerservice');
    }
}
