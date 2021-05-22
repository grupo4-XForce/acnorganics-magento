<?php

namespace Wbcom\Whatsapp\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    protected $formKey;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey $formKey
    )
    {
        $this->_objectManager = $objectmanager;
        $this->_scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->formKey = $formKey;
        parent::__construct($context);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return BaseUrl
     */
    public function getBaseUrl(){
        $storeManager = $this->storeManager;
        $baseUrl = $storeManager->getStore()->getBaseUrl();
        return $baseUrl;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * return MediaPath
     */
    public function getBaseMediaPath(){
        $storeManager = $this->storeManager;
        $mediaPath = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaPath;
    }

    /**
     * @return mixed
     */
    public function getModuleStatus(){
        return $this->_scopeConfig->getValue('wbcomwhatsapp/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getCoreConfigData(){
        $response = [];
        $response['title'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['mobile_view'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/enable_mobile', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['greeting_message'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/greeting_message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['msg'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['mobile'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/mobile_no', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['header'] = $this->_scopeConfig->getValue('wbcomwhatsapp/popup/header_back', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['popup_inner'] = $this->_scopeConfig->getValue('wbcomwhatsapp/popup/field_inner', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['btn_color'] = $this->_scopeConfig->getValue('wbcomwhatsapp/popup/btn_color', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['icon'] = $this->_scopeConfig->getValue('wbcomwhatsapp/general/whatsapp_icon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['icon_position'] = $this->_scopeConfig->getValue('wbcomwhatsapp/icon/icon_position', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['icon_position_bottom'] = $this->_scopeConfig->getValue('wbcomwhatsapp/icon/icon_position_bottom', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['icon_position_right'] = $this->_scopeConfig->getValue('wbcomwhatsapp/icon/icon_position_right', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $response['icon_position_left'] = $this->_scopeConfig->getValue('wbcomwhatsapp/icon/icon_position_left', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $response;
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
