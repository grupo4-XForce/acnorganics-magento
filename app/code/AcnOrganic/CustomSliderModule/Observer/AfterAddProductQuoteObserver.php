<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AcnOrganic\CustomSliderModule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class AfterAddProductQuoteObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Api\Data\CartItemInterface $quoteItem */
        $quoteItem = $observer->getQuoteItem();

        if ($quoteItem->getProductType() != "bundle") {
            return;
        }
        /** @var \Magento\Catalog\Api\Data\ProductInterface $bundleProduct */
        $bundleProduct = $quoteItem->getProduct();
        $addedQuantity = 0;
        $bundleExpectedQty= $bundleProduct->getLimitaCesta();

        if (!$bundleExpectedQty) {
            return;
        }

        $bundleItems = $quoteItem->getChildren();

        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        foreach ($bundleItems as $item) {
            $addedQuantity = $addedQuantity + $item->getQty();
        }

        if ($addedQuantity != $bundleExpectedQty) {

            throw new LocalizedException(__('Adicione a quantidade correta de produtos!'));
        }
    }
}
