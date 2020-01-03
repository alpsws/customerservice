<?php
namespace Alps\Customerservice\Controller\Adminhtml\Customerservice;

class Save extends \Magento\Backend\App\Action
{    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Alps\Customerservice\Model\CustomerserviceFactory $gridFactory,       
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem
    ) {  
        parent::__construct($context);       
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->gridFactory = $gridFactory;
    }

    public function execute()
    {   
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('customerservice/customerservice/addrow');
            return;
        }
         $rowData = $this->gridFactory->create();          
        try {  
         if(isset($_FILES['icon']) && isset($_FILES['icon']['name']) && strlen($_FILES['icon']['name'])){
                    $base_media_path = 'Alps/Customerservice';
                    $uploader = $this->uploader->create(
                        ['fileId' => 'icon']
                    );                
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png','svg']);               
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($base_media_path)
                    );                
                     $data['icon'] = $base_media_path.$result['file'];

                 }
             else{
                    if(isset($data['icon']['value'])){
                     $data['icon']=$this->checkIconvalue('icon',$data);
                    }               
                 }  

            if(isset($_FILES['hover_icon']) && isset($_FILES['hover_icon']['name']) && strlen($_FILES['hover_icon']['name'])){
                $base_media_path = 'Alps/Customerservice';
                $uploader = $this->uploader->create(
                    ['fileId' => 'hover_icon']
                );                
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png','svg']);               
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath($base_media_path)
                );
               
                $data['hover_icon'] = $base_media_path.$result['file'];                                  
            }
            else{
                    if(isset($data['hover_icon']['value'])){
                     $data['hover_icon']=$this->checkHoverIconvalue('hover_icon',$data);
                    }                
            }         
           
            $rowData->setData($data);
            if (isset($data['customer_service_id'])) {
                $rowData->setCustomerServiceId($data['customer_service_id']);
            }
            $rowData->save();

            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('customerservice/customerservice/index');
    }

    public function checkIconvalue($name,$data){
    
      if(isset($name))
      {
          
        if(array_key_exists("icon",$data))
        {   
            if (isset($data[$name]) && isset($data[$name]['value'])) {
                if (isset($data[$name]['delete'])) {
                    $data[$name] = "";
                     $data['delete_image'] = true;
                } else if (isset($data[$name]['value'])) {
                    $data[$name] = $data[$name]['value'];
                } else {
                    $data[$name] = "";
                }
            }
      
            return $data[$name];
        }
      }
        
    }

    
    public function checkHoverIconvalue($name,$data){    
    
      if(isset($name))
      {
        if(array_key_exists("hover_icon",$data))
        {
            if (isset($data[$name]) && isset($data[$name]['value'])) {
                if (isset($data[$name]['delete'])) {
                    $data[$name] = "";
                     $data['delete_image'] = true;
                } else if (isset($data[$name]['value'])) {
                    $data[$name] = $data[$name]['value'];
                } else {
                    $data[$name] = "";
                }
            }
            return $data[$name];
        }
        
      }
        
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Alps_Customerservice::save');
    }
}
