<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */

namespace Ksolves\Trackingorder\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Ksolves\Trackingorder\Helper\ConfigData $helperData
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ksolves\Trackingorder\Helper\ConfigData $helperData
    ) {
         $this->helperData = $helperData;
         parent::__construct($context);
    }
    
    /**
     * Button Color
     * @return String
     */
    public function getButtonColor()
    {
        return $this->helperData->getCompanyConfig('button_color');
    }

    /**
     * Button Text
     * @return String
     */
    public function getButtonText()
    {
        return $this->helperData->getCompanyConfig('button_text');
    }

    /**
     * Module Status
     * @return Int
     */
    public function getModuleStatus()
    {
        return $this->helperData->getCompanyConfig('enable');
    }  

    /**
     * Button Text Color
     * @return String
     */
    public function getButtonTextColor()
    {
        return  $this->helperData->getCompanyConfig('button_text_color');  
    }
}
