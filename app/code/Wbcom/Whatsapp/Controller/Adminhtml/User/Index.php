<?php

namespace Wbcom\Whatsapp\Controller\Adminhtml\User;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Visitors List"));
        $resultPage->addBreadcrumb(__('Visitors List'), __('Visitors List'));
        return $resultPage;
    }
}
