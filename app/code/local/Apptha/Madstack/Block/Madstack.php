<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Madstack
 * @version     0.1.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2015 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 * 
 */
class Apptha_Madstack_Block_Madstack extends Mage_Core_Block_Template {
	
	/**
	 * Configure product view blocks
	 *
	 * @return Mage_Checkout_Block_Cart_Item_Configure
	 */
	protected function _prepareLayout()
	{
	
		return parent::_prepareLayout();
	}
	
	public function storeStatusCollection($storeId){
		
		return Mage::getModel('madstack/storestatus')->load ( $storeId , 'store_id' );
	}
	
	
	/**
	* Function to get the product details
	*
	* Return product collection
	* 
	* @return array
	*/
	public function sliderProducts() {
		
		if(Mage::getStoreConfig('madstack/madstack/activate')){
			
			$currentPID = Mage::registry('current_product')->getId();
		
			$data = array('productID' => $currentPID , 'uuid' => Mage::getModel('core/cookie')->get('MADid') , 'numberResult' => (Mage::getStoreConfig('madstack/madstack/count_result') + 20));
			
			$resultArray = Mage::helper('madstack')->curlFunc($data,'https://contusapi.madstreetden.com/more');

			if($resultArray['status'] == 'success'){
				$products = Mage::getModel('catalog/product')
				->getCollection()
				->addAttributeToSelect('name')
				->addAttributeToSelect('price')
				->addFinalPrice('final_price')
				->addAttributeToSelect('product_url')
				->addAttributeToSelect('small_image')
				->addAttributeToFilter('entity_id', array('in' => $resultArray['data']))
				->addAttributeToFilter('status', 1);
				
			}else{
				$products = array();
			}
			
			return $products;
		}
	}
	
	/**
	 * Function to add tracking in view product detail page
	 *
	 * Return product productId,Category Name and product price for MAD Stack tracking
	 *
	 * @return json array
	 */
	public function viewProduct(){
		
		$arrProductView = array();
		
		$viewProductDetail = Mage::registry('current_product');
		if ($viewProductDetail) {
			$viewProductId				= $viewProductDetail->getId();
			$categories_name			= Mage::helper('madstack')->getCategoryName($viewProductId);
			$trackinArray				= Mage::helper('madstack')->buildTrackArray('pageView',$viewProductId,$categories_name);
			$trackinArray['prodPrice'] 	=  round($viewProductDetail->getFinalPrice(),2);
			$trackinArray['country']	= Mage::helper('madstack')->getCountry();
		
			return Mage::helper('madstack')->trackEvent($trackinArray);
		}
	}
	
	/**
	 * Function to add tracking for prodcuts added in cart
	 *
	 * Return product productId,Category Name and product price for MAD Stack tracking
	 *
	 * @return json array
	 */
	public function addToCart(){
		$addedToCart = Mage::getModel('core/session')->getProductToShoppingCart();
		
		$arrAddtoCart = array();
		
		if ($addedToCart && $addedToCart->getId()): 
			$trackinArray				= Mage::helper('madstack')->buildTrackArray('addToCart',$addedToCart->getId(),$addedToCart->getCategoryName());
			$trackinArray['prodPrice']	= $addedToCart->getPrice();
			$trackinArray['country']	= Mage::helper('madstack')->getCountry();
					
		 	Mage::getModel('core/session')->unsProductToShoppingCart();
		 	
		 	return Mage::helper('madstack')->trackEvent($trackinArray);
		 	
		  endif; 
	}
	
	/**
	 * Function to add tracking for checkout page
	 *
	 * Return product productId,Category Name and product price for MAD Stack tracking
	 *
	 * @return json array
	 */
	public function checkOutProducts(){
		$cart = Mage::getModel('checkout/cart')->getQuote();
		foreach ($cart->getAllItems() as $item) {
		    $productId		 = $productId.$item->getProduct()->getid().'_';
		    $categories_name =  $categories_name.Mage::helper('madstack')->getCategoryName($productId).'_';
		    $productPrice	 = $productPrice.round($productPrice.$item->getProduct()->getFinalPrice(),2).'_';
		    $productQty		 = $productQty.$item->getQty().'_';
		}
		
		$trackinArray 				= Mage::helper('madstack')->buildTrackArray('placeOrder',rtrim($productId,'_'),rtrim($categories_name,'_'));
		$trackinArray['prodPrice'] 	= rtrim($productPrice,'_');
		$trackinArray['prodQty'] 	= rtrim($productQty,'_');
		
		return Mage::helper('madstack')->trackEvent($trackinArray);
	}
	
	/**
	 * Function to add tracking for prodcuts added in cart
	 *
	 * Return product productId,Category Name and product price for MAD Stack tracking
	 *
	 * @return json array
	 */
	public function removeFromCart(){
		$removeFromCart = Mage::getModel('core/session')->getRemoveProduct();
	
		if ($removeFromCart && $removeFromCart->getId()):
			$trackinArray				= Mage::helper('madstack')->buildTrackArray('removeFromCart',$removeFromCart->getId(),$removeFromCart->getCategoryName());
			$trackinArray['prodPrice']	= $removeFromCart->getPrice();
			$trackinArray['country']	= Mage::helper('madstack')->getCountry();
				
			Mage::getModel('core/session')->unsRemoveProduct();
		
			return Mage::helper('madstack')->trackEvent($trackinArray);
	
		endif;
	}
} 