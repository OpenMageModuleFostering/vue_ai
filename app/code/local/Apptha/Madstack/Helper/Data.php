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


class Apptha_Madstack_Helper_Data extends Mage_Core_Helper_Abstract {
	
	/**
	 * Check Madstack module enable/disable
	 *
	 * @return boolen value
	 */
	
	function checkMadstackEnable() {
		return Mage::getStoreConfig('madstack/madstack/activate');
	}
	
	/**
	 * Method to check the authendication 
	 *
	 * if a valid user, send the product details to respecive server.
	 *
	 * @param $authParams as authendication key and action in encrypted format
	 *
	 * @return array
	 *
	 */
	public function authendication($authParams) {
		if(!empty($authParams)){
			$decodeUrl = $this->decryptData($authParams);
				
			parse_str($decodeUrl,$parameters);
			
			$consumerDet =  Mage::getModel('madstack/installid')->getCollection();
	
			if(!empty($consumerDet)){
				foreach($consumerDet as $consumerData){
					$consumerSecret = $consumerData->getMadstackUuid();
				}
			}
			if($parameters ['install_id'] == $consumerSecret){
				$result = array('success');
				$this->sendResponse ( 200, json_decode ( $result ) );
			
				$action = $parameters['action'];
			
				try {
					switch ($action) {
					   /*
						* Fetch the all products from the site
						*/
						case "allProducts" :
							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							}else{
								$result[] = Mage::helper('madstack')->allproducts($parameters ['storeId']);
								$this->sendResponse ( 200, json_encode ( $result ) );
								break;
							}
							 
						/*
						 * Fetch the products based on the timestamp
						 */
						case "timePeriod" :

							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							}else{
								if (empty($parameters ['from']) && empty($parameters ['to'])) {
									$this->sendResponse ( 303, json_encode ( array (
											'error' => true,
											'message' => 'Product Creation Date parameter is missing'
									) ) );
								} else if (!empty($parameters ['from'])) {
									$result[] = Mage::helper('madstack')->dateStampProducts($parameters ['from'],$parameters ['to']);
									$this->sendResponse ( 200, json_encode ( $result ) );
								}else{
									$this->sendResponse ( 303, json_encode ( array (
											'error' => true,
											'message' => 'Parameter vale missed in datestamp action.'
									) ) );
								}
							}
			
							break;
						/*
						 * Fetch the products based on the productID
						 */
						case "productDetail" :
			
							if (empty($parameters ['productid'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'Product ID parameter is missing'
								) ) );
							}else if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							} else {
								$result[] = Mage::helper('madstack')->ProductDetail($parameters ['productid'],$parameters ['storeId']);
								$this->sendResponse ( 200, json_encode ( $result ) );
							}
							break;
							 
						/*
						 * Fetch the products based on Updated date
						 */
						case "updatedProducts" :
							 
							if (empty($parameters ['from']) && empty($parameters ['to'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'Updated Product Date parameter is missing'
								) ) );
							} else if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							} else if (!empty($parameters ['from'])) {
								$result[] = Mage::helper('madstack')->updatedProducts($parameters ['from'],$parameters ['to'],$parameters ['storeId']);
								$this->sendResponse ( 200, json_encode ( $result ) );
							}else{
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'Parameter vale missed in updated products.'
								) ) );
							}
							break;
							 
						/*
						* Fetch the products qty and price based on the productID
						*/
						case "getInventory" :
							 
							if (empty($parameters ['productid'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'Product ID parameter is missing'
								) ) );
							} else if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							}else {
								$result[] = Mage::helper('madstack')->getInventory($parameters ['productid'],$parameters ['storeId']);
								$this->sendResponse ( 200, json_encode ( $result ) );
							}
							break;
							
						/*
						 * Fetch the products qty and price based on the productID
						 */
						case "getCategory" :
							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							}else{
								$result[] = Mage::helper('madstack')->getCategory($parameters ['storeId']);
								$this->sendResponse ( 200, json_encode ( $result ) );
							}
							break;
							
						/*
						 * Getting the store List.
						 */
						case "storeList" :
							
						$allStores = Mage::app()->getStores();
						foreach ($allStores as $_eachStoreId => $val) 
						{
							$result[$_eachStoreId] = $this->storeInformation($_eachStoreId);
						}
						$this->sendResponse ( 200, json_encode ( $result ) );
							
						break;
						
						/*
						 * Getting the store details.
						*/
						case "storeDetails" :
								
							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'StoreId parameter is missing'
								) ) );
							}else{
								$storeId	= $parameters ['storeId'];
								$result[] = $this->storeInformation($storeId);	
							}
							$this->sendResponse ( 200, json_encode ( $result ) );
								
							break;
						
						/*
						 * Getting the time zone for a particular product.
						*/
						case "timeZone" :
							
							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
										'error' => true,
										'message' => 'storeId parameter is missing'
								) ) );
							} else {
								$storeId	= $parameters ['storeId'];
								
								$result[] = array(
										'id' => Mage::app()->getStore($storeId)->getId(),
										'name' => Mage::app()->getStore($storeId)->getName(),
										'catalog_url' => Mage::app()->getStore($storeId)->getUrl(),
										'time_zone'	=> Mage::app()->getStore($storeId)->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
								);
							}
							
							$this->sendResponse ( 200, json_encode ( $result ) );
								
							break;
							
						/*
						 * Getting the store status for a madstack.
						 */
						case "storeStatus" :
									
							if (empty($parameters ['storeId'])) {
								$this->sendResponse ( 303, json_encode ( array (
									'error' => true,
									'message' => 'storeId parameter is missing.'
								) ) );
							} else {
								$data = array('store_id' => $parameters['storeId'],'domain' => Mage::getBaseURL());
								Mage::getModel('madstack/storestatus')->storeStatus($data);
							}
										
							$this->sendResponse ( 200, json_encode ( $result ) );
								
							break;
						/*
						 * Getting the time zone for a particular product.
						 */
						 case "fashionCategory" :
						 	$Catageory = array(1=>'Jewery' , 2=> 'Clothing' , 3=>'Handy craft',4=>'Home Decor',5=>'Earrings',6 => 'Necklaces',7=>'Bangles',8=>'Saree',9=>'Salwar Kameez',10=>'Bollywood Sarees',11=>'Designer Sarees',12=>'Bollywood suit',13=>'Anarkali Suit',14=>'Marble',15=>'Wooden',16=>'Wall & door Hangings',17=>'Lighting Lamps',18=>'Candle Holders');
							
						 	$subCategory = array(0=>array(1,2,3,4),1=>array(5,6,7),2=>array(8,9),3=>array(14,15,16),4=>array(17,18),8=>array(10,11),9=>array(12,13));
						 	
						 	$resultArray = array();
						 	foreach($subCategory as $key => $value){
						 		foreach($value as $parentKey => $subcategoryList){
						 			$resultArray[] = array('id' => $subcategoryList,'name' => $Catageory[$subcategoryList],'parent_id' => $key);
						 		}
						 		
						 	}
						 	$result = array_merge($result,$resultArray);
						 	$this->sendResponse ( 200, json_encode ( $result ) );
						 	
						 	break;
						 	
				
						/*
						 * Default switch case for request action failes
						 */
						default :
							$this->sendResponse ( 405, json_encode ( array (
							'error' => true,
							'message' => 'Method Not Allowed'
							) ) );
							break;
									 
					}
				}catch ( OAuthException $e ) {
			
					$errorMessage = json_decode ( $e->lastResponse );
					$error ['message'] = $errorMessage->messages->error [0]->message;
					$error ['code'] = $errorMessage->messages->error [0]->code;
			
					$this->sendResponse ( $error ['code'], json_encode ( array (
							'error' => true,
							'message' => (isset ( $error ['message'] )) ? $error ['message'] : $e->getMessage (),
							'success' => 0
					) ) );
				}
			
			}else{
				$this->sendResponse ( 400, json_encode ( array (
						'error' => true,
						'message' => 'Bad Request'
				) ) );
			}
		}else{
			$this->sendResponse ( 303, json_encode ( array (
					'error' => true,
					'message' => 'Authendication parameter is empty'
			) ) );
		}
		
		return;
	}
	
	/**
	 * Method to send response as json format in the body
	 *
     * @param $status, staus code for authendication result
     * @param $body, result value
     * @param $content_type, result in json format
     * 
	 * @return array
	 *
	 */
	
	function sendResponse($status = 200, $body = '', $content_type = 'application/json') {
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage ( $status );
		header ( $status_header );
		header ( 'Content-type: ' . $content_type );
		echo $body;
	}
	
	/**
	 * Method to get the status code from the url to check whether it is valid or not
	 *
	 * @param $status, authendication result
	 *
	 * @return array
	 *
	 */
	
	function getStatusCodeMessage($status)
	{
		$codes = Array (
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => '(Unused)',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported'
		);
	
		return (isset ( $codes [$status] )) ? $codes [$status] : '';
	}
	
	
	/**
	 * Function to get product collection
	 *
	 * Return product collection
	 *
	 * @return object
	 */
	public function productsCollection($store_id) {
		
		try{
			return Mage::getModel('catalog/product')->getCollection()->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('status', array('eq' => 1));
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	/**
	 * Function to get all products
	 *
	 * Return all products array
	 *
	 * @return array
	 */
	public function allproducts($store_id) {
		$allProducts =  $this->productsCollection($store_id);
		return $this->commonArrayCollection($allProducts);
	}
	
	/**
	 * Function to product details based on date of created product
	 *
	 * @param $fromDate whether it is from date for collect the created product details
	 * @param $toDate whether it is for date for collect the created product details 
	 *
	 * @return array
	 */
	public function dateStampProducts($fromDate = '',$toDate = '') {
	   /*
		* Here collect a product detail from date to end date.
		*/
		if(!empty($fromDate) && !empty($toDate)){
			$allProductsCollection = $this->productsCollection($store_id)
					->addAttributeToFilter('created_at', array('from'=>date("Y-m-d H:i:s", $fromDate), 'to'=>date("Y-m-d H:i:s", $toDate)));
			
		}else{
		   /*
			* Here collect a product detail for the from date and to date is same.
			*/
			$allProductsCollection = $this->productsCollection($store_id)				
						->addAttributeToFilter('created_at', array('from'=>date("Y-m-d H:i:s", $fromDate),  'to'=>date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")))));
		}
		return $this->productsArray($allProductsCollection);
	}
	
	/**
	 * Function to product details based on product updated date
	 *
	 * @param $fromDate whether it is from date for collect the updated product details
	 * @param $toDate whether it is for date for collect the updated product details
	 *
	 * @return array
	 */
	public function updatedProducts($fromDate = '',$toDate = '',$store_id) {
		/*
		 * Here collect a product detail from date to end date.
		*/
		if(!empty($fromDate) && !empty($toDate)){
			$allProductsCollection = $this->productsCollection($store_id)
			->addAttributeToFilter('updated_at', array('from'=>date("Y-m-d H:i:s", $fromDate), 'to'=>date("Y-m-d H:i:s", $toDate)))
			->addAttributeToFilter('updated_at', array ('neq' => new Zend_Db_Expr('created_at')));
				
		}else{
			/*
			 * Here collect a product detail for the from date and to date is same.
			*/
			$allProductsCollection = $this->productsCollection($store_id)
			->addAttributeToFilter('updated_at', array('from'=>date("Y-m-d H:i:s", $fromDate), 'to'=>date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")))))
			->addAttributeToFilter('updated_at', array ('neq' => new Zend_Db_Expr('created_at')));
		}
		return $this->prodInventoryArray($allProductsCollection);
	}
	/**
	 * Function to get product details based on product id.
	 *
	 * @param $PID whether it is a product id.
	 *
	 * @return array
	 */
	public function ProductDetail($PID,$store_id) {
	
		$allProductsCollection = $this->productsCollection($store_id)->addAttributeToFilter('entity_id', array('eq' => $PID));
	
		return $this->productsArray($allProductsCollection);
	}
	
	/**
	 * Function to get product inventory and price based on product id
	 *
	 * @param $PID whether it is a product id
	 *
	 * @return array
	 */
	public function getInventory($PID) {
	
		$allProductsCollection = $this->productsCollection($store_id)->addAttributeToFilter('entity_id', array('eq' => $PID));
	
		return $this->prodInventoryArray($allProductsCollection);
	}
	
	/**
	 * Function for common result, for each action
	 *
	 * @param $ProductsCollection, it is a product collection.
	 *
	 * @return array
	 */
	public function commonArrayCollection($ProductsCollection) {
		return Mage::helper('madstack')->productsArray($ProductsCollection);
	}
	
	/**
	 * Function for product details into array
	 *
	 * @param $productDetails, it is a collection of product detail
	 *
	 * @return array
	 */
	public function productsArray($productDetails) {
		$arrProduct = array();
		if(!empty($productDetails)){
			foreach($productDetails as $prod) {
				$productQty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prod->getId())->getQty();
				
				/*
				 * Getting discount percentage
				 */
				$originalPrice = $prod->getPrice();
				$finalPrice = $prod->getFinalPrice();
				$percentage = 0;
				
				if ($originalPrice > $finalPrice) {
					$percentage = ($originalPrice - $finalPrice) * 100 / $originalPrice;
				}
				
				/*
				 * Get original image path for the products.
				*/
				$varProductId = $prod->getId();
				$productMediaConfig = Mage::getModel('catalog/product_media_config');
				$originalImageUrl = $productMediaConfig->getMediaUrl($prod->getImage());
				
				$arrProduct[$varProductId] = array(
						'id'=>$varProductId,
						'name' => $prod->getName(),
						'desc'=>$prod->getDescription(),
						'url'=>$prod->getProductUrl(),
						'category'=>$prod->getCategoryIds(),
						'qty' => round($productQty, 2),
						'retail_price' => round($prod->getPrice(), 2),
						'sale_price' =>round($prod->getFinalPrice(), 2),
						'discount' => $percentage,
						'image' => $originalImageUrl,
						'sku' => $prod->getSku(),
						'store_id' => $prod->getStoreIds()
				);
			}
			
		}
		/*
		 * If product deatils are empty, return valu as NULL value
		 */
		if(empty($arrProduct)){
			$arrProduct = null;
		}
		return $arrProduct;
	}
	
	/**
	 * Function to get registered category list
	 *
	 * @return array.
	 */
	public function getCategory($storeid) {
		
		$storeRootCategoryId = Mage::app()->getStore($storeid)->getRootCategoryId();
		
		Mage::getSingleton('core/session')->setCategoryId(array($storeRootCategoryId));
		
		Mage::helper('madstack')->getSubCategories($storeRootCategoryId);
		
		$getCategoryIds = Mage::getSingleton('core/session')->getCategoryId();
		
		$categories = Mage::getModel('catalog/category')
		->getCollection()
		->addAttributeToSelect('id')
		->addAttributeToSelect('parent_id')
		->addAttributeToSelect('name')
		->addFieldToFilter('entity_id', array("in"=>$getCategoryIds))
		->load();
		
		foreach ($categories as $category) {
			$resultArray[] = array('id' => $category->getId(),'name' => $category->getName(),'parent_id' => $category->getParentId());
		}
		
		Mage::getSingleton('core/session')->unsCategoryId();

		return $resultArray;

	}
	
	/**
	 * Function to get children cartegory id
	 *
	 * @return 
	 */
	
	public function getSubCategories($parentId,$rootCategory,$childLevel){
		
	    $cat = Mage::getModel('catalog/category')->load($parentId);
	    $subcats = $cat->getChildren();
	    $getCategory = Mage::getSingleton('core/session')->getCategoryId();
	    
	   
	    if(!empty($subcats)){
	    	$explodeSubCategory = explode(',',$subcats);
	    	$mergeCategoryId = array_merge($explodeSubCategory,$getCategory);
	    	
	    	Mage::getSingleton('core/session')->setCategoryId($mergeCategoryId);
	    	
		    foreach($explodeSubCategory as $subCatid)
		    {
		    	$_category = Mage::getModel('catalog/category')->load($subCatid);
		    	Mage::helper('madstack')->getSubCategories($_category->getId());
		    }
		  
	    }
		return ; 
	}
	
	/**
	 * Function for track event response code for MAD Stack server, when track an event.
	 *
	 * @parms $productId, set product id as parameter.
	 *
	 * @return string.
	 */
	public function categoryName($categoryId){
	
		$getProductDetail = Mage::getModel('catalog/product')->load($productId);
		$cats = $getProductDetail->getCategoryIds();
	
		return Mage::getModel('catalog/category')->load($cats[0])->getName();
	}
	
	/**
	 * Function for product qty and price into array
	 *
	 * @param $productDetails, it is a collection of product detail
	 *
	 * @return array
	 */
	public function prodInventoryArray($productDetails) {
		$arrProduct = array();
		if(!empty($productDetails)){
			foreach($productDetails as $prod) {
				$productQty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prod->getId())->getQty();
				$arrProduct[$prod->getId()] = array(
						'qty' => round($productQty, 2),
						'price' => round($prod->getPrice(), 2)
				);
			}
				
		}
		return $arrProduct;
	}
	
	/**
	 * Function for encrypt data
	 * 
	 * @param $varData, authendication key for encrypt the data 
	 * 
	 * @return string
	 */
	public function encryptData($varData) {
	
		return base64_encode($varData);
	}
	
	/**
	 * Function for decrypt data
	 *
	 * @param $varData, decrypt the authendication key
	 *
	 * @return string
	 */
	public function decryptData($varData) {
	
		return base64_decode($varData);
	}
	
	/**
	 * Function for used to send request for get product collection from Madstack.
	 *
	 * Product collection used in product detail page slider.
	 *
	 * @return string
	 */
	public function curlFunc($data,$feed_url){
	
		try{
			$curl = new Zend_Http_Client();
			
			foreach($data as $key => $value){
				$curl->setParameterPost($key, $value);
			}
			$curl->setMethod(Zend_Http_Client::POST);
			$curl->setUri($feed_url);
		
			$jsonData = $curl->request();
			return Mage::helper('core')->jsonDecode($jsonData->getBody($jsonData));
		}catch (Exception $e){
			$e->error('error');
		}
		
	}
	
	/**
	 * Function for track event response code for MAD Stack server, when track an event.
	 *
	 * @return string
	 */
	public function trackEvent($data){
		return '<script type="text/javascript">track('.json_encode($data).')</script>';
		
	}
	
	/**
	 * Function for track event response code for MAD Stack server, when track an event.
	 *
	 * @return string
	 */
	public function buildTrackArray($event,$productId,$categoryName){
	
		$arrTracking['event'] 		 = $event;
		$arrTracking['sourceProdID'] = $productId;
		$arrTracking['sourceCatgID'] = $categoryName;
		$arrTracking['vendor'] 		 = Mage::getBaseURL();
		
		return $arrTracking;
	
	}
	
	/**
	 * Function for track event response code for MAD Stack server, when track an event.
	 *
	 * @parms $productId, set product id as parameter. 
	 *
	 * @return string.
	 */
	public function getCategoryName($productId){
	
		$getProductDetail = Mage::getModel('catalog/product')->load($productId);
		$cats = $getProductDetail->getCategoryIds();
	
		return Mage::getModel('catalog/category')->load($cats[0])->getName();
	}
	
	/**
	 * Function to get country name.
	 *
	 * @return string
	 */
	public function getCountry(){
	
		$geocode	= file_get_contents('https://freegeoip.net/json/');
		$output		= json_decode($geocode);
		
		return $output->country_name;
	}
	
	/**
	 * Function to generate installation id and domain name
	 *
	 * @return boolean.
	 */
	public function generateUUID(){
		$randomNum = '';
		for($i = 0; $i < 16; $i++) {
	        $randomNum .= rand(0, 9);
	    }
	    $collection = Mage::getModel ( 'madstack/installid' );
	    $collection->setMadstackUuid ( $randomNum );
	    $collection->setDomainName ( Mage::getBaseURL() );
	    $collection->setCreatedTime ( Mage::getModel('core/date')->date('Y-m-d H:i:s') );
	    $collection->save();
	    
	    return $randomNum;
	}
	
	public function storeInformation($store_id){
		
		$store_information = array(
			'id' => Mage::app()->getStore($store_id)->getId(),
			'name' => Mage::app()->getStore($store_id)->getName(),
			'catalog_url' => Mage::app()->getStore($store_id)->getUrl(),
			'time_zone'	=> Mage::app()->getStore($store_id)->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
		);
		
		return $store_information;
	}
	
	
}	 