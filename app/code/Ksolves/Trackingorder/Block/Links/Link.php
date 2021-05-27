<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */
namespace Ksolves\Trackingorder\Block\Links;

class Link extends \Magento\Framework\View\Element\Html\Link
{
	/**
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Ksolves\Trackingorder\Helper\ConfigData $helperData
     */
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager,
	    \Magento\Framework\View\Element\Template\Context $context,
	    \Ksolves\Trackingorder\Helper\ConfigData $helperData,
	    array $data = []
	) {
		$this->_storeManager = $storeManager;
		$this->helperData = $helperData;
	    parent::__construct($context, $data);
	}

	/**
     *  return url of link
     * @param \Ksolves\Trackingorder\Helper\ConfigData $helperData
     * @return String
     */
	public function getHref(){
	 	$ks_enableLink = $this->helperData->getCompanyConfig('allow_toplink');
	 	if($ks_enableLink == 1) {
		 	$baseurl = $this->_storeManager->getStore()->getBaseUrl();
		    $page_url =''.$baseurl.'trackingorder'; 
		    return $this->getUrl($page_url);
		}
	}

	/**
     *  return label of link
     * @param \Ksolves\Trackingorder\Helper\ConfigData $helperData
     * @return  String
     */
	public function getLabel(){
		$ks_enableLink = $this->helperData->getCompanyConfig('allow_toplink');
	 	if($ks_enableLink == 1) {
	        return 'Track Order';
	    }
	}
}

