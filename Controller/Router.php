<?php

namespace Alps\Customerservice\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Alps\Customerservice\Model\CustomerserviceFactory;


class Router implements RouterInterface
{
    protected $actionFactory;
    protected $eventManager;
    protected $response;
    protected $dispatched;
    protected $gridCollection;
    protected $storeManager;
    protected $CustomerserviceFactory;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        ManagerInterface $eventManager,
        CustomerserviceFactory $CustomerserviceFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->response = $response;
        $this->CustomerserviceFactory = $CustomerserviceFactory;
        $this->storeManager = $storeManager;
    }

    public function match(RequestInterface $request)
    {
        if (!$this->dispatched) {
            $urlKey = trim($request->getPathInfo(), '/');
            $origUrlKey = $urlKey;
            $condition = new DataObject(['url_key' => $urlKey, 'continue' => true]);
            $this->eventManager->dispatch(
                'alps_customerservice_controller_router_match_before',
                ['router' => $this, 'condition' => $condition]
            );
            $urlKey = $condition->getUrlKey();
            if ($condition->getRedirectUrl()) {
                $this->response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }
            $identifiers = explode('/', $urlKey); 
            if(count($identifiers) == 1) {               
                $identifier = $identifiers[0];
                $dataModel = $this->CustomerserviceFactory->create();             
                $serviceCollection = $dataModel->getCollection()
                    ->addFieldToFilter('status', ['eq' => 1])
                    ->addFieldToFilter('url', array('eq' => $identifier))
                    ->addStoreFilter($this->storeManager->getStore())
                    ->getFirstItem();               
                if ($serviceCollection && $serviceCollection->getCustomerServiceId()) {
                    $request->setModuleName('customerservice')
                        ->setControllerName('index')
                        ->setActionName('view')
                        ->setParam('customer_service_id', $serviceCollection->getCustomerServiceId());
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
        }
    }
}
