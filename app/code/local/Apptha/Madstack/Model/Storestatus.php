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
class Apptha_Madstack_Model_Storestatus extends Mage_Core_Model_Abstract {
	
	/*
	 * MAD Stack request URL for collect store enable/disable status.
	 */
	
	public function _construct() {
		parent::_construct();
    	$this->_init('madstack/storestatus');
	}
	
	/*
	 * function can be used to store the 'STORE' enable/disable status 
	 * 
	 * return boolean
	 */
	public function storeStatus($data){
		
		$resultArray = Mage::helper('madstack')->curlFunc($data,'https://contus.madstreetden.com/store/');

		$collection = Mage::getModel('madstack/storestatus')->load ( $resultArray['id'] , 'store_id' );
		
		/*
		 * Store the log url and api url in admin configuration only one time.
		 */
		if(empty(Mage::getStoreConfig('madstack/madstack/product_log_url_status'))){
			if(!empty($resultArray['tracking_url'])){
				Mage::getModel('core/config')->saveConfig('madstack/madstack_api_url', $resultArray['carousal_api']);
				Mage::getModel('core/config')->saveConfig('madstack/madstack_log_url', $resultArray['tracking_url']);
				Mage::getModel('core/config')->saveConfig('madstack/madstack/product_log_url_status', 1);
			}
		}
		if($resultArray['carousal_enable'] == false || empty($resultArray['carousal_enable'])){
			$enableOption = 0;
		}else{
			$enableOption = 1;
		}
		
		if($resultArray['license_valid'] == false || empty($resultArray['license_valid'])){
			$license_valid = 0;
		}else{
			$license_valid = 1;
		}
		
		if($resultArray['digestion_status'] == false || empty($resultArray['digestion_status'])){
			$digestion_status = 0;
		}else{
			$digestion_status = 1;
		}
		
		if(empty($collection->getStoreId())){
			$collection = Mage::getModel('madstack/storestatus');
			$collection->setStoreId($resultArray['id']);
			$collection->setLicenseValid($license_valid);
			$collection->setCarousalEnable($enableOption);
			$collection->setDigestionStatus($digestion_status);
			$collection->setCreatedTime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
			$collection->save();
		}else{
			$collection->setStoreId($resultArray['id']);
			$collection->setLicenseValid($license_valid);
			$collection->setCarousalEnable($enableOption);
			$collection->setDigestionStatus($digestion_status);
			$collection->setUpdatedTime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
			$collection->save();
		}
		return;
	}
	
	
}