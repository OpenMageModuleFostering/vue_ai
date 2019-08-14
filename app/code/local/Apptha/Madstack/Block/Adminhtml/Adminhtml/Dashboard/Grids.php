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
?>

<?php
class Apptha_Madstack_Block_Adminhtml_Dashboard_Grids extends Mage_Adminhtml_Block_Dashboard_Grids {
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $this->addTab('custom', array(
            'label'     => $this->__('Product viewed'),
            'content'   => 'Total products viewed '.$this->viewCount(),
        ));
    }
    
    protected function viewCount(){
    	$db = Mage::getSingleton('core/resource')->getConnection('core_read');
		$result = $db->query("select count('views') as count from report_event");
		
		$view = $result->fetch(PDO::FETCH_ASSOC);
		$finalcount = empty((int)$view['count']) ? 0 :  (int)$view['count'] ;
		
		return $finalcount;
    }
}

?>
