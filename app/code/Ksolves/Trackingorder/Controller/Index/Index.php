<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */
namespace Ksolves\Trackingorder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
   /**
    * Page Factory
    *
    * @var \Magento\Framework\View\Result\PageFactory
    */
 	  protected $_pageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pagefactory
     */
 	  public function __construct(
 		     \Magento\Framework\App\Action\Context $context,
 		     \Magento\Framework\View\Result\PageFactory $pagefactory
 	  ) {
 		     $this->_pageFactory = $pagefactory;
 		     return parent::__construct($context);
 	  }

 	/**
     * trackingorder  action
     * @return \Magento\Framework\View\Result\PageFactory
     */
 	public function execute()
 	{
 	   $this->resultPage = $this->_pageFactory->create(); 
       $this->resultPage->getConfig()->getTitle()->set((__('Track Order')));
       return $this->resultPage;   
 	}
}
