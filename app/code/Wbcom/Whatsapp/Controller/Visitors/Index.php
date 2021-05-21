<?php

namespace Wbcom\Whatsapp\Controller\Visitors;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Wbcom\Whatsapp\Model\WhatsappFactory
     */
    protected $whatsappFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    /**
     * @var bool
     */
    protected $resultPageFactory = false;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Wbcom\Whatsapp\Model\WhatsappFactory $whatsappFactory
     * @param RemoteAddress $remoteAddress
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Wbcom\Whatsapp\Model\WhatsappFactory $whatsappFactory,
        RemoteAddress $remoteAddress,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_logger = $logger;
        $this->remoteAddress = $remoteAddress;
        $this->_pageFactory = $pageFactory;
        $this->whatsappFactory = $whatsappFactory;
        $this->_resultFactory = $context->getResultFactory();
        return parent::__construct($context);
    }

    public function execute()
    {
        $postData = $this->getRequest()->getParams();
        $ip = $this->remoteAddress->getRemoteAddress();
        $model = $this->whatsappFactory->create();
        $model->setCustomerName($postData['name']);
        $model->setCustomerEmail($postData['email']);
        $model->setCustomerIp($ip);
        try{
            $model->save();
        }catch(\Exception $e){
            $this->_logger->error('Error while saving customer whatsapp data'.$e->getMessage());
        }
        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirectPath = 'https://api.whatsapp.com/send?phone='.$postData['mobile'].'&text='.$postData['message'].'&source=&data=';
        $resultRedirect->setUrl($redirectPath);
        return $resultRedirect;
        return $this->_pageFactory->create();
    }
}
