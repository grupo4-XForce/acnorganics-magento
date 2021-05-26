<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category  BSS
 * @package   Bss_Limitcartqty
 * @author    Extension Team
 * @copyright Copyright (c) 2018-2019 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\Limitcartqty\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class UpdateCustomerGroup
 *
 * @package Bss\Limitcartqty\Observer
 */
class UpdateCustomerGroup implements ObserverInterface
{
    /**
     * @var \Bss\Limitcartqty\Helper\CheckoutFlag
     */
    protected $checkoutFlag;

    /**
     * UpdateCustomerGroup constructor.
     *
     * @param \Bss\Limitcartqty\Helper\CheckoutFlag $checkoutFlag
     */
    public function __construct(
        \Bss\Limitcartqty\Helper\CheckoutFlag $checkoutFlag
    ) {
        $this->checkoutFlag = $checkoutFlag;
    }

    /**
     * Reset Cart
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->checkoutFlag->resetCart();
    }
}
