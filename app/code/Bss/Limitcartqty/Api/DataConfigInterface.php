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
namespace Bss\Limitcartqty\Api;

interface DataConfigInterface
{
    /**
     * MinValue
     *
     * @return mixed
     */
    public function getMinValue();

    /**
     * MaxValue
     *
     * @return mixed
     */
    public function getMaxValue();

    /**
     * CustomerId
     *
     * @return mixed
     */
    public function getCustomerId();

    /**
     * Store Id
     *
     * @return mixed
     */
    public function getStoreId();
}
