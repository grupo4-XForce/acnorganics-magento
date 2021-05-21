<?php

namespace Wbcom\Whatsapp\Controller\Adminhtml\User;
use Wbcom\Whatsapp\Model\WhatsappFactory;
use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var bool
     */
    protected $resultPageFactory = false;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param WhatsappFactory $whatsappFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        WhatsappFactory $whatsappFactory
    )
    {
        parent::__construct($context);
        $this->_resultFactory = $context->getResultFactory();
        $this->whatsappFactory = $whatsappFactory;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->whatsappFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__("Visitor chat data delete successfully."));
            } catch (\Exception $e) {
                $this->messageManager->addError('Something went wrong '.$e->getMessage());
            }
        }else{
            $this->messageManager->addError('Visitor data not found, please try once more.');
        }
        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('wbcomwhatsapp/user/index');
        return $resultRedirect;
    }
}
