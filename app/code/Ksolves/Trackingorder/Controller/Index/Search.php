<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */
namespace Ksolves\Trackingorder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface ;

class Search extends \Magento\Framework\App\Action\Action
{
    private $repository;
    private $request;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Ksolves\Trackingorder\Helper\ConfigData $helperData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        Context $context,
        Repository $repository,
        RequestInterface $request
    ) {
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->helperData = $helperData;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_pageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->_storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->repository = $repository;
        $this->request = $request;
        parent::__construct($context);
    }
     
    /**
     * trackingorder search  action
     *
     * @return \Magento\Framework\View\Result\resultRedirectFactory
     * @return search result
     */
    public function execute()
    {
        $id  = $this->getRequest()->getPost('order_id');
        $data  = $this->getOrderId($id);
        if ($data) {
            $orderId = $this->getOrderId($id);
        } else {
            $orderId = $id;
        }
        $emailId = $this->getRequest()->getPost('email_address');
        $orderdata = $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
        $orderItems = $orderdata->getAllItems();
        if (!$orderItems) {
            $this->messageManager->addErrorMessage(__('Please enter valid order id'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('trackingorder/index/index');
        } elseif ($emailId != $orderdata->getCustomerEmail()) {
            $this->messageManager->addErrorMessage(__('Please enter correct email id'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('trackingorder/index/index');
        } else {
            $this->registry->register('orderdata', $orderdata);
            $this->resultPage = $this->_pageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set((__('Track Order')));
            return $this->resultPage;
        }
    }

    /**
     * return order Id
     * @return  int
     */
    public function getOrderId($incrementId)
    {
        $orderRepository = $this->_objectManager->create('\Magento\Sales\Model\OrderRepository');
        $searchCriteriaBuilder = $this->_objectManager->create('\Magento\Framework\Api\SearchCriteriaBuilder');
        $searchCriteriaBuilder->addFilter('increment_id', $incrementId);
        $order = $orderRepository->getList($searchCriteriaBuilder->create())->getItems();
        foreach ($order as $key => $value) {
            return $value->getId();
        }
    }
}
