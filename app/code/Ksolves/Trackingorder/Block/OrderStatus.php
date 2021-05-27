<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Trackingorder
 */

namespace Ksolves\Trackingorder\Block;

use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface ;

class OrderStatus extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Ksolves\Trackingorder\Helper\ConfigData $helperData
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Ksolves\Trackingorder\Helper\ConfigData $helperData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\View\Element\Template\Context $context,
        Repository $repository,
        RequestInterface $request
    ) {
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->helperData = $helperData;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_pageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->_objectManager = $objectmanager;
        $this->_storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->repository = $repository;
        $this->request = $request;
        parent::__construct($context);
    }
    
    /**
     * Order Collection
     * @return String
     */
    public function getOrderData()
    {
        return $this->registry->registry('orderdata');
    }

    /**
     * Items Ordered Data
     * @return String
     */
    public function getItemsOrderedData()
    {
        $orderdata   = $this->getOrderData();
        $orderItems  = $orderdata->getAllItems();
        $baseurl = $this->_storeManager->getStore()->getBaseUrl();
        $fileSystem = $this->_objectManager->create('\Magento\Framework\Filesystem');
        $currency_symbol = $this->getCurrencySymbol($orderdata);
        $store = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $mediaPath = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageHelper = $this->_objectManager->get(\Magento\Catalog\Helper\Image::class);
        $discount     = explode('-', $orderdata->getDiscountAmount());
        $result = '';
        $result.= 
        '<div class="column main">    
            <div class="order-details-items ordered order-items-outer ">
                <div class="order-title">
                    <strong>Items Ordered</strong>
                </div>
                <div class="table-wrapper order-items mb-0">
                    <table class="data table table-order-items" id="my-orders-table" summary="Items Ordered">
                        <caption class="table-caption">Items Ordered</caption>
                        <thead class="product-table-heading" >
                            <tr>
                                <th class="col image">Product Image</th>
                                <th class="col name">Product Name</th>
                                <th class="col sku">SKU</th>
                                <th class="col price">Price</th>
                                <th class="col qty">Qty</th>
                                <th class="col subtotal">Subtotal</th>
                            </tr>
                        </thead>';
                        $itemSku ="";
                        $childProductCount = 0;
                        $border ="";
                        foreach($orderItems as $orderItemst)
                        {
                            $productRepositoryFactory = $this->_objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterfaceFactory');
                            $_product = $productRepositoryFactory->create()->getById($orderItemst->getProductId());
                            $productType    =$orderItemst->getProductType();
                            if ($itemSku == $orderItemst['sku']) {
                                continue;
                            }
                            $itemSku = $orderItemst['sku'];
                            $productVisibility = $_product->getAttributeText('visibility')->getText(); 
                            $imgaeProps = explode(".",$_product->getImage());  // check image available or not then set default image.
                            $productImage = (isset($imgaeProps[1]) && !empty($imgaeProps[1])) ? $mediaPath.'catalog/product' .$_product->getImage() : $imageHelper->getDefaultPlaceholderUrl('small_image');
                            if ($productType == "bundle") {
                               $childProductCount = $this->getbundleChildProductCount($orderItemst->getProductId());
                            }
                            if ($childProductCount < 4) {
                                $border = "0px";
                            }
                            $childProductCount++;
                            $result.=
                        '<tbody>
                            <tr id="order-item-row-4" >';
                            if ($productVisibility == "Not Visible Individually") {
                                $result.='<td class="col image" data-th="Product Image">
                                  <img  src="'.$productImage.'">
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                      '.$orderItemst['name'].'
                                    </strong>';
                                    if($attribute = $this->getconfigProdAttr($orderItemst)) {
                                        $result.= $attribute;
                                    }
                                $result.='</td>';
                                 } else { 
                                $result.='
                                <td class="col image" data-th="Product Image">
                                    <a href="'.$baseurl.'catalog/product/view/id/'.$orderItemst->getProductId().'/"><img  src="'.$productImage.'">
                                    </a>
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                        <a href="'.$baseurl.'catalog/product/view/id/'.$orderItemst->getProductId().'/">'.$orderItemst['name'].'</a>
                                    </strong>';
                                    if($attribute = $this->getconfigProdAttr($orderItemst)) {
                                        $result.= $attribute;
                                    }
                                $result.='</td>';                      
                                }         
                                $result.='<td class="col sku" data-th="SKU">'.$orderItemst['sku'].'</td>
                                <td class="col price" data-th="Price">';
                                if ($productType == "bundle") {
                                    continue;
                                }
                                $result.='
                                <span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                        <span class="price">'.$currency_symbol.number_format($orderItemst['price'], 2).'</span>            
                                    </span>
                                </span>
                                </td>
                                <td class="col qty" data-th="Qty">
                                    <ul class="items-qty">
                                        <li class="item">
                                            <span class="content">'.number_format($orderItemst['qty_ordered'], 0).'</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="col subtotal" data-th="Subtotal">
                                <span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                       <span class="price">'.$currency_symbol.number_format($orderItemst['base_row_total_incl_tax'], 2).'</span>            
                                       </span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>';              
                        }
                        $result.=
                        '<tfoot>';
                        $result.='
                            <tr class="subtotal">;
                                <th colspan="5" class="mark" scope="row">Subtotal</th>
                                    <td class="amount" data-th="Subtotal">
                                        <span class="price">'.$currency_symbol.number_format($orderdata['base_subtotal'], 2).'</span>                    
                                    </td>
                            </tr>';
                           if ($orderdata->getDiscountAmount()!=0) {
                                $result.=
                                '<tr class="discount">
                                <th colspan="5" class="mark" scope="row">Discount</th>
                                    <td class="amount" data-th="Subtotal">
                                        <span class="price">-'.$currency_symbol.number_format($discount[1], 2).'</span>                    
                                    </td>
                            </tr>';
                            }
                            if ($orderdata['base_shipping_amount']!=0) {
                               $result.='
                                <tr class="shipping">
                                    <th colspan="5" class="mark" scope="row">Shipping &amp; Handling</th>
                                        <td class="amount" data-th="Shipping &amp; Handling">
                                            <span class="price">'.$currency_symbol.number_format($orderdata['base_shipping_amount'], 2).'</span>                    
                                        </td>
                                </tr>';
                            }
                            if ($orderdata['base_tax_amount']!=0) {
                                $result.=
                                '<tr class="totals-tax">
                                <th colspan="5" class="mark" scope="row">Tax</th>
                                <td class="amount" data-th="Tax">
                                    <span class="price">'.$currency_symbol.number_format($orderdata['base_tax_amount'], 2).'</span>   
                                </td>
                            </tr>';
                            }
                            $result.=
                            '<tr class="grand_total">
                                <th colspan="5" class="mark" scope="row"><strong>Grand Total</strong></th>
                                <td class="amount" data-th="Grand Total">
                                    <strong><span class="price">'.$currency_symbol.number_format($orderdata['base_grand_total'], 2).'</span></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>';     
        return $result;
    }

    /**
     * Items Ordered Data
     * @return String
     */
    public function getInvoiceData()
    {
        $orderdata         = $this->getOrderData();
        foreach ($orderdata->getInvoiceCollection() as $invoice) {
            $invoiceId     =  $invoice->getEntityId();
            $incrementId   =  $invoice->getIncrementId();
        }
        $invoiceData       = $this->_objectManager->create('\Magento\Sales\Model\Order\Invoice')->load($invoiceId);
        $invoiceItems      = $invoiceData->getAllItems();
        $baseurl           = $this->_storeManager->getStore()->getBaseUrl();
        $fileSystem        = $this->_objectManager->create('\Magento\Framework\Filesystem');
        $currency_symbol   = $this->getCurrencySymbol($orderdata);
        $store             = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $mediaPath         = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageHelper       = $this->_objectManager->get(\Magento\Catalog\Helper\Image::class);
        $discount          = explode('-', $invoiceData->getDiscountAmount());
        $result = '';
        $result.=
        '<div class="flex mb-25" >
        <div><span class="primary-text" >Invoice:</span>#'.$incrementId.'</div>
        </div>
        <div class="column main">    
            <div class="order-details-items ordered order-items-outer ">
                <div class="order-title">
                    <strong>Items Ordered</strong>
                </div>
                <div class="table-wrapper order-items mb-0">
                    <table class="data table table-order-items" id="my-orders-table" summary="Items Ordered">
                        <caption class="table-caption">Items Ordered</caption>
                        <thead class="product-table-heading" >
                            <tr>
                                <th class="col image">Product Image</th>
                                <th class="col name">Product Name</th>
                                <th class="col sku">SKU</th>
                                <th class="col price text-center">Price</th>
                                <th class="col qty text-center">Qty Invoiced</th>
                                <th class="col subtotal">Subtotal</th>
                            </tr>
                        </thead>';
                        $itemSku ="";
                        foreach($invoiceItems as $invoiceItemst)
                        {
                            $productRepositoryFactory = $this->_objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterfaceFactory');
                            $_product = $productRepositoryFactory->create()->getById($invoiceItemst->getProductId());
                            $productType    = $_product->getTypeID();
                            if ($itemSku == $invoiceItemst->getSku()) {
                                continue;
                            }
                            $itemSku = $invoiceItemst->getSku();
                            $productVisibility = $_product->getAttributeText('visibility')->getText(); 
                            $imgaeProps = explode(".",$_product->getImage());  // check image available or not then set default image.
                            $productImage = (isset($imgaeProps[1]) && !empty($imgaeProps[1])) ? $mediaPath.'catalog/product' .$_product->getImage() : $imageHelper->getDefaultPlaceholderUrl('small_image');
                            $result.=
                        '<tbody>
                            <tr id="order-item-row-4">';
                            if ($productVisibility == "Not Visible Individually") {
                                $result.='<td class="col image" data-th="Product Image">
                                  <img  src="'.$productImage.'">
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                      '.$invoiceItemst->getName().'
                                    </strong>';
                                    if($attribute = $this->getconfigProdAttr($invoiceItemst)) {
                                        $result.= $attribute;
                                    }
                                $result.='</td>';
                                 } else {     
                                $result.='
                                <td class="col image" data-th="Product Image">
                                    <a href="'.$baseurl.'catalog/product/view/id/'.$invoiceItemst->getProductId().'/"><img  src="'.$productImage.'">
                                    </a>
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                        <a href="'.$baseurl.'catalog/product/view/id/'.$invoiceItemst->getProductId().'/">'.$invoiceItemst->getName().'
                                    </strong>';
                                    if($attribute = $this->getconfigProdAttr($invoiceItemst)) {
                                        $result.= $attribute;
                                    }
                                $result.='</td>';              
                                }    
                                $result.='<td class="col sku" data-th="SKU">'.$invoiceItemst->getSku().'</td>
                                <td class="col price text-center" data-th="Price">';
                                if ($productType == "bundle") {
                                      continue;
                                  }  
                                $result.='<span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                        <span class="price">'.$currency_symbol.number_format($invoiceItemst->getPrice(), 2).'</span>            
                                    </span>
                                </span>
                                </td>
                                <td class="col qty text-center" data-th="Qty">
                                    <ul class="items-qty">
                                        <li class="item">
                                            <span class="content">'.number_format($invoiceItemst->getQty(), 0).'</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="col subtotal" data-th="Subtotal">
                                <span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                       <span class="price">'.$currency_symbol.number_format($invoiceItemst->getBaseRowTotalInclTax(), 2).'</span>            
                                       </span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>';              
                        }
                        $result.=
                        '<tfoot>';
                        $result.=
                            '<tr class="subtotal">
                                <th colspan="5" class="mark" scope="row">Subtotal</th>
                                    <td class="amount" data-th="Subtotal">
                                        <span class="price">'.$currency_symbol.number_format($invoiceData->getSubtotal(), 2).'</span>                    
                                    </td>
                            </tr>';
                            if ($invoiceData->getDiscountAmount()!=0) {
                                $result.=
                            '<tr class="discount">
                                <th colspan="5" class="mark" scope="row">Discount</th>
                                    <td class="amount" data-th="Subtotal">
                                        <span class="price">-'.$currency_symbol.number_format($discount[1], 2).'</span>                    
                                    </td>
                            </tr>';
                            }
                            if ($invoiceData->getBaseShippingAmount()!=0) {
                                $result.=
                            '<tr class="shipping">
                                <th colspan="5" class="mark" scope="row">Shipping &amp; Handling</th>
                                    <td class="amount" data-th="Shipping &amp; Handling">
                                        <span class="price">'.$currency_symbol.number_format($invoiceData->getBaseShippingAmount(), 2).'</span>                    
                                    </td>
                            </tr>';
                            }
                            if ($invoiceData->getBaseTaxAmount()!=0) {
                                $result.=
                            ' <tr class="totals-tax">
                                <th colspan="5" class="mark" scope="row">Tax</th>
                                <td class="amount" data-th="Tax">
                                    <span class="price">'.$currency_symbol.number_format($invoiceData->getBaseTaxAmount(), 2).'</span>   
                                </td>
                            </tr>';
                            }
                            $result.=
                            '<tr class="grand_total">
                                <th colspan="5" class="mark" scope="row"><strong>Grand Total</strong></th>
                                <td class="amount" data-th="Grand Total">
                                    <strong><span class="price">'.$currency_symbol.number_format($invoiceData->getBaseGrandTotal(), 2).'</span></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>';     
        return $result;
    }

    /**
     * Items Ordered Data
     * @return String
     */
    public function getShipmentData()
    {
        $orderdata          = $this->getOrderData();
        foreach ($orderdata->getShipmentsCollection() as $shipment) {
          $shipmentId     = $shipment->getEntityId();
          $incrementId    = $shipment->getIncrementId();
        }
        $shipmentData     = $this->_objectManager->create('\Magento\Sales\Model\Order\Shipment')->load($shipmentId);
        $shipmentItems    = $shipmentData->getAllItems();
        $baseurl          = $this->_storeManager->getStore()->getBaseUrl();
        $fileSystem       = $this->_objectManager->create('\Magento\Framework\Filesystem');
        $currency_symbol  = $this->getCurrencySymbol($orderdata);
        $store            = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $mediaPath        = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageHelper      = $this->_objectManager->get(\Magento\Catalog\Helper\Image::class);
        $result = '';
        $result.= 
        '<div class="flex mb-25" >
        <div><span class="primary-text" >Shipment:</span>#'.$incrementId.'</div>
        </div>
        <div class="column main">    
            <div class="order-details-items ordered order-items-outer ">
                <div class="order-title">
                    <strong>Items Ordered</strong>
                </div>
                <div class="table-wrapper order-items mb-0">
                    <table class="data table table-order-items" id="my-orders-table" summary="Items Ordered">
                        <caption class="table-caption">Items Ordered</caption>
                        <thead class="product-table-heading" >
                            <tr>
                                <th class="col image">Product Image</th>
                                <th class="col name">Product Name</th>
                                <th class="col sku text-center">SKU</th>
                                <th class="col qty">Qty Shipped</th>
                            </tr>
                        </thead>';
                        $itemSku ="";
                        foreach($shipmentItems as $shipmentItemst)
                        {
                            $productRepositoryFactory = $this->_objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterfaceFactory');
                            $_product = $productRepositoryFactory->create()->getById($shipmentItemst->getProductId());
                            $productType    = $_product->getTypeID();                            
                            $itemSku = $shipmentItemst->getSku();
                            $productVisibility = $_product->getAttributeText('visibility')->getText(); 
                            $imgaeProps = explode(".",$_product->getImage());  // check image available or not then set default image.
                            $productImage = (isset($imgaeProps[1]) && !empty($imgaeProps[1])) ? $mediaPath.'catalog/product' .$_product->getImage() : $imageHelper->getDefaultPlaceholderUrl('small_image'); 
                            $result.=
                        '<tbody>
                            <tr id="order-item-row-4">';
                            if ($productVisibility == "Not Visible Individually") {
                                $result.='<td class="col image" data-th="Product Image">
                                  <img  src="'.$productImage.'">
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                      '.$shipmentItemst->getName().'
                                    </strong>
                                </td>';
                                 } else { 
                                $result.='
                                <td class="col image" data-th="Product Image">
                                    <a href="'.$baseurl.'catalog/product/view/id/'.$shipmentItemst->getProductId().'/"><img  src="'.$productImage.'">
                                    </a>
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                        <a href="'.$baseurl.'catalog/product/view/id/'.$shipmentItemst->getProductId().'/">'.$shipmentItemst->getName().'
                                    </strong>
                                </td>';                      
                                }         
                                $result.='<td class="col sku text-center" data-th="SKU">'.$shipmentItemst->getSku().'</td>
                                <td class="col qty" data-th="Qty">
                                    <ul class="items-qty">
                                        <li class="item">
                                            <span class="content">'.number_format($shipmentItemst->getQty(), 0).'</span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>';              
                        }
                        $result.=
                    '</table>
                </div>
            </div>
        </div>';     
        return $result;
    }

    /**
     * Items Ordered Data
     * @return String
     */
    public function getRefundsData()
    {
        $orderdata       = $this->getOrderData();
        foreach ($orderdata->getCreditmemosCollection() as $creditMemo) {
          $creditMemoId = $creditMemo->getEntityId();
          $incrementId  = $creditMemo->getIncrementId();
        }
        $refundsData    = $this->_objectManager->create('\Magento\Sales\Model\Order\CreditMemo')->load($creditMemoId);
        $refundsItems   = $refundsData->getAllItems();
        $baseurl        = $this->_storeManager->getStore()->getBaseUrl();
        $fileSystem     = $this->_objectManager->create('\Magento\Framework\Filesystem');
        $currency_symbol= $this->getCurrencySymbol($orderdata);
        $store          = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $mediaPath      = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageHelper    = $this->_objectManager->get(\Magento\Catalog\Helper\Image::class);
        $discount       = explode('-', $refundsData->getDiscountAmount());
        $result = '';
        $result.=
        '<div class="flex mb-25" >
        <div><span class="primary-text" >Refund:</span>#'.$incrementId.'</div>
        </div> 
        <div class="column main">    
            <div class="order-details-items ordered order-items-outer ">
                <div class="order-title">
                    <strong>Items Ordered</strong>
                </div>
                <div class="table-wrapper order-items mb-0">
                    <table class="data table table-order-items" id="my-orders-table" summary="Items Ordered">
                        <caption class="table-caption">Items Ordered</caption>
                        <thead class="product-table-heading" >
                            <tr>
                                <th class="col image">Product Image</th>
                                <th class="col name">Product Name</th>
                                <th class="col sku">SKU</th>
                                <th class="col price">Price</th>
                                <th class="col qty">Qty</th>
                                <th class="col subtotal">Subtotal</th>
                                <th class="col discount text-center">Discount</th>
                                <th class="col total">Row Total</th>
                            </tr>
                        </thead>';
                        $itemSku ="";
                        foreach($refundsItems as $refundsItemst)
                        {
                            $productRepositoryFactory = $this->_objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterfaceFactory');
                            $_product =$productRepositoryFactory->create()->getById($refundsItemst->getProductId());
                            $productType   = $_product->getTypeID();
                            if ($itemSku == $refundsItemst->getSku()) {
                                continue;
                            }
                            $itemSku = $refundsItemst->getSku();
                            $productVisibility = $_product->getAttributeText('visibility')->getText(); 
                            $imgaeProps = explode(".",$_product->getImage());  // check image available or not then set default image.
                            $productImage = (isset($imgaeProps[1]) && !empty($imgaeProps[1])) ? $mediaPath.'catalog/product' .$_product->getImage() : $imageHelper->getDefaultPlaceholderUrl('small_image');
                            $result.=
                        '<tbody>
                            <tr id="order-item-row-4">';
                            if ($productVisibility == "Not Visible Individually") {
                                $result.='<td class="col image" data-th="Product Image">
                                  <img  src="'.$productImage.'">
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                      '.$refundsItemst->getName().'
                                    </strong>
                                </td>';
                                 } else { 
                                $result.='
                                <td class="col image" data-th="Product Image">
                                    <a href="'.$baseurl.'catalog/product/view/id/'.$refundsItemst->getProductId().'/"><img  src="'.$productImage.'">
                                    </a>
                                </td>
                                <td class="col name" data-th="Product Name">
                                    <strong class="product name product-item-name">
                                        <a href="'.$baseurl.'catalog/product/view/id/'.$refundsItemst->getProductId().'/">'.$refundsItemst->getName().'
                                    </strong>
                                </td>';                      
                                }         
                                $result.='<td class="col sku" data-th="SKU">'.$refundsItemst->getSku().'</td>
                                <td class="col price" data-th="Price">';  
                                if ($productType == "bundle") {
                                      continue;
                                  } 
                                $result.='
                                <span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                        <span class="price">'.$currency_symbol.number_format($refundsItemst->getPrice(), 2).'</span>            
                                    </span>
                                </span>
                                </td>
                                <td class="col qty" data-th="Qty">
                                    <ul class="items-qty">
                                        <li class="item">
                                            <span class="content">'.number_format($refundsItemst->getQty(), 0).'</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="col subtotal" data-th="Subtotal">
                                <span class="price-excluding-tax" data-label="Excl. Tax">
                                    <span class="cart-price">
                                       <span class="price">'.$currency_symbol.number_format($refundsItemst->getBaseRowTotalInclTax(), 2).'</span>            
                                       </span>
                                    </span>
                                </td>
                                <td class="col subtotal" data-th="Discount">
                                <span class="price-excluding-tax text-center" data-label="Excl. Tax">
                                    <span class="cart-price">
                                       <span class="price">-'.$currency_symbol.number_format($refundsItemst->getDiscountAmount(), 2).'</span>            
                                       </span>
                                    </span>
                                </td>
                                <td class="col subtotal" data-th="Total amount">
                                <span class="price-excluding-tax text-center" data-label="Excl. Tax">
                                    <span class="cart-price">
                                       <span class="price">'.$currency_symbol.number_format(($refundsItemst->getBaseRowTotal()-$refundsItemst->getDiscountAmount()), 2).'</span>            
                                       </span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>';              
                        }
                        $result.=
                        '<tfoot>';
                        $result.=
                            '<tr class="subtotal">
                            <th colspan="7" class="mark" scope="row">Subtotal</th>
                                 <td class="amount" data-th="Subtotal">
                                      <span class="price">'.$currency_symbol.number_format($refundsData->getBaseSubtotalInclTax(), 2).'</span>                    
                                 </td>
                            </tr>';
                            if ($refundsData->getDiscountAmount()!=0) {
                               $result.=
                            '<tr class="discount">
                                <th colspan="7" class="mark" scope="row">Discount</th>
                                     <td class="amount" data-th="Subtotal">
                                          <span class="price">-'.$currency_symbol.number_format($discount[1], 2).'</span>                    
                                     </td>
                            </tr>';
                            }
                            if ($refundsData->getBaseShippingAmount()!=0) {
                               $result.=
                            '<tr class="shipping">
                                <th colspan="7" class="mark" scope="row">Shipping &amp; Handling</th>
                                     <td class="amount" data-th="Shipping &amp; Handling">
                                          <span class="price">'.$currency_symbol.number_format($refundsData->getBaseShippingAmount(), 2).'</span>                    
                                     </td>
                            </tr>';
                            }
                            if ($refundsData->getAdjustmentPositive()!=0) {
                                $result.=
                            '<tr class="adjustment positive">
                                <th colspan="7" class="mark" scope="row">Adjustment Refund</th>
                                     <td class="amount" data-th="Shipping &amp; Handling">
                                          <span class="price">'.$currency_symbol.number_format($refundsData->getAdjustmentPositive(), 2).'</span>                    
                                     </td>
                            </tr>';
                            }
                            if ($refundsData->getAdjustmentNegative()!=0) {
                                $result.=
                            '<tr class="adjustment negative">
                                <th colspan="7" class="mark" scope="row">Adjustment Fee</th>
                                     <td class="amount" data-th="Shipping &amp; Handling">
                                          <span class="price">'.$currency_symbol.number_format($refundsData->getAdjustmentNegative(), 2).'</span>                    
                                     </td>
                            </tr>';
                            }
                            if ($refundsData->getBaseTaxAmount()!=0) {
                                $result.=
                            '<tr class="tax amount">
                                <th colspan="7" class="mark" scope="row">Tax </th>
                                     <td class="amount" data-th="Shipping &amp; Handling">
                                          <span class="price">'.$currency_symbol.number_format($refundsData->getBaseTaxAmount(), 2).'</span>                    
                                     </td>
                            </tr>';
                            }
                            $result.=
                            '<tr class="grand_total">
                                     <th colspan="7" class="mark" scope="row"><strong>Grand Total</strong></th>
                                     <td class="amount" data-th="Grand Total">
                                          <strong><span class="price">'.$currency_symbol.number_format($refundsData->getBaseGrandTotal(), 2).'</span></strong>
                                     </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>';     
        return $result;
    }
    
     /**
      * get oder status in readable form 
      *
      * @return string
      */

    public function getOrderStatus($orderStatus)
    {
        switch ($orderStatus) {
            case 'pending':
                return "Pending";
                break;
            case 'fraud':
                return "Suspected Fraud";
                break;
            case 'pending_payment':
                return "Pending Payment";
                break;
            case 'payment_review':
                return "Payment Review";
                break;
            case 'processing':
                return "Processing";
                break;
            case 'holded':
                return "On Hold";
                break;
            case 'STATE_OPEN':
                return "Open";
                break;
            case 'complete':
                return "Complete";
                break;
            case 'closed':
                return "Closed";
                break;
            case 'canceled':
                return "Cancelled";
                break;
            case 'paypay_canceled_reversal':
                return "PayPal Canceled Reversal";
                break;
            case 'pending_paypal':
                return "Pending PayPal";
                break;
            case 'paypal_reversed':
                return "PayPal Reversed";
                break;
        }
    }

    /**
     * @msg 
     * @return currency symbol
     */

    public function getCurrencySymbol($orderdata)
    {
        $order_currency_code = $orderdata->getOrderCurrencyCode(); 
        $currency = $this->_objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($order_currency_code);
        return $currency->getCurrencySymbol();
    }

    /**
     * invoice data
     * @return  string
     */
    public function invoiceData($order)
    {
        $invoiceCollection = $order->getInvoiceCollection();
        foreach($invoiceCollection as $invoice){
          return $invoice->getIncrementId();
        }
    }

    /**
     * shipment data
     * @return  string
     */
    public function shipmentData($order)
    {
        $shipments = $order->getShipmentsCollection();
        foreach($shipments as $shipment){
          return $shipment->getIncrementId();
        }
    }

    /**
     * refunds data
     * @return  string
     */
    public function refundsData($order)
    {
        $creditMemos = $order->getCreditmemosCollection();
        foreach ($creditMemos as $creditMemo) { 
          return $creditMemo->getIncrementId();
        }
    }

    /**
     * get shipping address
     * @return  string
     */
    public function getShippingInfo($orderObj)
    {
        $shippingAddressObj                = $orderObj->getShippingAddress();
        $shippingAddressArray              = $shippingAddressObj->getData(); 
        $result ='
            <address>
            '.$shippingAddressArray['firstname'].' '.$shippingAddressArray['lastname'].'<br>
            '.$shippingAddressArray['email'].'<br>
            '.$shippingAddressArray['street'].'<br>
            '.$shippingAddressArray['city'].','.$shippingAddressArray['postcode'].'<br>
            '.$shippingAddressArray['region'].'<br>
            T: '.$shippingAddressArray['telephone'].'
            </address>';
        return $result;
    }

    /**
     * get billing info
     * @return  string
     */
    public function getBillingInfo($orderObj)
    {
        $BillingAddressObj                 = $orderObj->getBillingAddress();
        $BillingAddressArray               = $BillingAddressObj->getData();
        $result ='
            <address>
            '.$BillingAddressArray['firstname'].' '.$BillingAddressArray['lastname'].'<br>
            '.$BillingAddressArray['email'].'<br>
            '.$BillingAddressArray['street'].'<br>
            '.$BillingAddressArray['city'].' ,  '.$BillingAddressArray['postcode'].'<br>
            '.$BillingAddressArray['region'].'<br>
            T:'.$BillingAddressArray['telephone'].'
            </address>';
        return $result;
    }


    /**
     * get order product type
     * @return  string
     */
    public function getOrderProductType($orderdata)
    {
        $orderItems = $orderdata->getAllItems();
        foreach($orderItems as $orderItemst)
        {
            $productType    =$orderItemst->getProductType();
        }
        return $productType;
    }


    /**
     * bundle products
     * @return  string
     */
    public function getconfigProdAttr($_item)
    {
        $result="";
        if($_options = $this->getSelectedOptions($_item)) {
        foreach ($_options as $_option) : 
            $result.= '<b>'.$_option['label']."</b>  :  ".$_option['value']."<br>";
        endforeach; 
        }
        return $result;
    }

    /**
     * check the attribute of configurable attribute
     * @return  string
     */

    function getSelectedOptions($item)
    {
        $result = [];
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }
        return $result;
    }


    public function getbundleChildProductCount($productId)
    {
        $productCount = 0;
        $_storeManager = $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface');
        $store_id = $_storeManager->getStore()->getId();
        $options = $this->_objectManager->get('Magento\Bundle\Model\Option')->getResourceCollection()
        ->setProductIdFilter($productId);
         $options->joinValues($store_id);
         foreach ($options as $key => $option) {
            $productCount = $option->getOptionId();
         }
         return $productCount;
    }
    
}
