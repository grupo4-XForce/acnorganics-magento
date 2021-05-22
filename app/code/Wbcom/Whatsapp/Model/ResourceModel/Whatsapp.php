<?php

namespace Wbcom\Whatsapp\Model\ResourceModel;

class Whatsapp extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'id';
    /**
     * Initialize construct
     */
    public function _construct()
    {
        $this->_init("wbcom_whatsapp_chat", "id");
    }
}
