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
class Apptha_Madstack_Adminhtml_IframeController extends Mage_Adminhtml_Controller_action {
	
	public function indexAction() {
		//Get current layout state
		$this->loadLayout()->_setActiveMenu('madstack');
		 
		$block = $this->getLayout()->createBlock(
		'Mage_Core_Block_Template',
		'madstack/adminhtml_madstackiframe',
		array('template' => 'madstack/iframe.phtml')
		);
		 
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
} 