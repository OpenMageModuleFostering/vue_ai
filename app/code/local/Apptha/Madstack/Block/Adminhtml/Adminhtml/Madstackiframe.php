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
class Apptha_Madstack_Block_Adminhtml_Madstackiframe extends Mage_Adminhtml_Block_Widget_Grid_Container {

    /**
     * Construct the inital display of grid information
     * Setting the Block files group for this grid
     * Setting the Header text to display
     * Setting the Controller file for this grid
     * 
     * Return seller information as array
     * @return array
     */
    public function __construct() {
        $this->_controller = 'adminhtml_iframecontroller';
        $this->_blockGroup = 'madstack';
        $this->_headerText = Mage::helper('marketplace')->__('Madstack Iframe');
        parent::__construct();
    }

}

