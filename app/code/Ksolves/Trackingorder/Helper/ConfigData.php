<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */

namespace Ksolves\Trackingorder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ConfigData extends AbstractHelper
{
	const XML_PATH_TRACKORDER = 'trackingorder/';

 	/**
     * @return Magento\Store\Model\ScopeInterface
     */
	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

	/**
     * @return string
     */
	public function getCompanyConfig($code, $storeId = null)
	{
	
		return $this->getConfigValue(self::XML_PATH_TRACKORDER .'trackordersetting/'. $code, $storeId);
	}

}
