<?php

namespace Wbcom\Whatsapp\Model\Config\Source;

class ListMode implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'right', 'label' => __('Right')],
            ['value' => 'left', 'label' => __('Left')]
        ];
    }
}