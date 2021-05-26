<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */
namespace Ksolves\Trackingorder\Plugin\Block;

use Magento\Framework\Data\Tree\NodeFactory;

class Topmenu
{

    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Data\Tree\NodeFactory $nodeFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        NodeFactory $nodeFactory,
        \Ksolves\Trackingorder\Helper\ConfigData $helperData
    ) {
        $this->_storeManager = $storeManager;
        $this->nodeFactory = $nodeFactory;
        $this->helperData = $helperData;
    }

    /**
     * return menu 
     *
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $node = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }

     /**
     * return menu label
     *
     */    
    protected function getNodeAsArray()
    {
        
        $ks_enableLink = $this->helperData->getCompanyConfig('allow_topmenu');
        if($ks_enableLink == 1)
        {
            $baseurl = $this->_storeManager->getStore()->getBaseUrl();
            return [
                'name' => __('Track Order'),
                'id' => 'track_order',
                'url' => ''.$baseurl.'trackingorder',
                'has_active' => false,
                'is_active' => false
            ];
        }   
    }
}