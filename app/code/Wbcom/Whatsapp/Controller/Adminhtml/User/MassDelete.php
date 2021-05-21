<?php
namespace Wbcom\Whatsapp\Controller\Adminhtml\User;

class MassDelete extends \Magento\Backend\App\Action {
    
    protected $_filter;

    protected $_collectionFactory;

    /**
     * MassDelete constructor.
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Wbcom\Whatsapp\Model\ResourceModel\Whatsapp\CollectionFactory $collectionFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Wbcom\Whatsapp\Model\ResourceModel\Whatsapp\CollectionFactory $collectionFactory,
        \Magento\Backend\App\Action\Context $context
        ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute() {
        try{
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $itemsDelete = 0;
            foreach ($collection as $item) {
                $item->delete();
                $itemsDelete++;
            }
            $this->messageManager->addSuccess(__('A total of %1 visitors(s) data were deleted successfully.', $itemsDelete));
        }catch(Exception $e){
            $this->messageManager->addError('Something went wrong while deleting the visitor '.$e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('wbcomwhatsapp/user/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Wbcom_Whatsapp::view');
    }
}