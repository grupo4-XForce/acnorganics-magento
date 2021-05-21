<?php

namespace Wbcom\Whatsapp\Model;


class Whatsapp extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize construct
     */
    public function _construct()
    {
        $this->_init("Wbcom\Whatsapp\Model\ResourceModel\Whatsapp");
    }
}
