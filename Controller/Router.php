<?php

namespace Magiccart\Shopfranchise\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;
    protected $_franchise;
    protected $helper;
    protected $_response;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magiccart\Shopfranchise\Model\ShopfranchiseFactory $franchise,
        \Magiccart\Shopfranchise\Helper\Data $helper
    )
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->_franchise = $franchise;
        $this->helper = $helper;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if(!$this->helper->getConfigModule('general/enabled')) return;
        $identifier = trim($request->getPathInfo(), '/');
        $router     = $this->helper->getRouter();
        $urlSuffix  = $this->helper->getUrlSuffix();
        if ($length = strlen((string) $urlSuffix)) {
            if (substr($identifier, -$length) == $urlSuffix) {
                $identifier = substr($identifier, 0, strlen($identifier) - $length);
            }
        }

        $routePath = explode('/', (string) $identifier);
        $routeSize = sizeof($routePath); //den count //

        if ($identifier == $router) {
            $request->setModuleName('shopfranchise')
                    ->setControllerName('franchise')
                    ->setActionName('listfranchise')
                    ->setPathInfo('/shopfranchise/franchise/listfranchise');
            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');

        } elseif ($routeSize >= 2 && $routePath[0] == $router) {
            $url_key = "";
            foreach($routePath as $key => $value){
                if($key == 0 ) continue;
                $url_key .= ($key == 1) ?  $value : "/".$value;
            }
            $model = $this->_franchise->create();
            $franchiseLoad = $model->load($url_key, 'urlkey');

            if (!empty($franchiseLoad)) {
                $id = $franchiseLoad->getData('shopfranchise_id');
                if($id){
                    $request->setModuleName('shopfranchise')
                        ->setControllerName('franchise')
                        ->setActionName('view')
                        ->setParam('id', $id)
                        ->setPathInfo('/shopfranchise/franchise/view');
                    return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
                }
            }
        } else {
            return;
        }
    }
}
