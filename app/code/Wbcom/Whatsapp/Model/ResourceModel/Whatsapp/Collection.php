<?php

namespace Wbcom\Whatsapp\Model\ResourceModel\Whatsapp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * Initialize construct
     */
    public function _construct()
    {
        $this->_init(
            'Wbcom\Whatsapp\Model\Whatsapp',
            'Wbcom\Whatsapp\Model\ResourceModel\Whatsapp'
        );
    }
}
