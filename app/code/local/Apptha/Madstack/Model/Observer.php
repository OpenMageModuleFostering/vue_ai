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
 */

/**
 * Event Observer
 */
class Apptha_Madstack_Model_Observer {
	
	/**
	 * Add external js on header of the page.
	 *
	 * @param array $observer        	
	 *
	 * @return void
	 */
	public function addHeader(Varien_Event_Observer $observer) {
		
 		$controller = $observer->getAction();
 		
 		$layout = $controller->getLayout();
 		$block = $layout->createBlock('core/text');
		
 		$block->setText('<script src="'.Mage::getStoreConfig('madstack/madstack_log_url').'"></script>');
 		if(Mage::app()->getLayout()->getBlock('head')){
 			Mage::app()->getLayout()->getBlock('head')->append($block);
 		}
 		return;
 
	}
	
	/**
	 * After category changes, send request to MAD Stack
	 *
	 */
	public function categoryChanges(Varien_Event_Observer $observer){
		
		$installIdCollection = Mage::getModel('madstack/installid')->getCollection();
							
		foreach($installIdCollection as $value){
			$installid = $value->getMadstackUuid();
		}
							
		Mage::helper('madstack')->curlFunc(array('domain'=>Mage::getBaseURL(),'install_id'=>$installid),'http://contus.madstreetden.com/install/');
		return;
		
	}
	
	/**
	 * Add tracking for after adding a product into Cart.
	 *
	 * Product details can be stored in session and tracking added based on these session values.
	 *
	 */
	public function trackAddToCart() {
	
		$product = Mage::getModel('catalog/product')
		->load(Mage::app()->getRequest()->getParam('product', 0));
	
		if (!$product->getId()) {
			return;
		}
	
		$categories = $product->getCategoryIds();
	
		Mage::getModel('core/session')->setProductToShoppingCart(
		new Varien_Object(array(
			'id' => $product->getId(),
			'qty' => Mage::app()->getRequest()->getParam('qty', 1),
			'name' => $product->getName(),
			'price' => round($product->getFinalPrice(),2),
			'category_name' => Mage::getModel('catalog/category')->load($categories[0])->getName(),
			))
		);
		return;
	}
	
	/**
	 * Add tracking for after adding a product into Whislist.
	 *
	 * Product ID can be stored in session
	 *
	 */
	public function addProductToWishlist($observer){
		
		$itemId = Mage::app()->getRequest()->getParam('product');
		
		if (!$itemId) {
			return;
		}
		Mage::getModel('core/session')->setWhislistProductId($itemId);
		
		return;
	}
	
	/**
	 * Add tracking for after adding a product into Whislist.
	 *
	 * Product ID can be stored in session
	 *
	 */
	public function removeFromCart($observer){
	
		$product = $observer->getQuoteItem()->getProduct();

		if (!$product->getEntityId()) {
			return;
		}
		$productPrice = empty($product->getSpecialPrice()) ? $product->getPrice() : $product->getSpecialPrice();
	
		Mage::getModel('core/session')->setRemoveProduct(
		new Varien_Object(array(
			'id' => $product->getEntityId(),
			'price' => round($productPrice,2),
			'category_name' => Mage::helper('madstack')->getCategoryName($product->getEntityId())
			))
		);
		
		return;
	}
	
}
