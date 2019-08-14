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
$installer = $this;

$installer->startSetup();

$installer->run("
            CREATE TABLE IF NOT EXISTS {$this->getTable('madstack_installid')} (
              `sno` int(11) unsigned NOT NULL auto_increment,
              `domain_name` varchar(255) NULL default '',
              `madstack_uuid` varchar(100) NOT NULL default '',
              `created_time` datetime NULL,
              PRIMARY KEY (`sno`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         
            CREATE TABLE IF NOT EXISTS {$this->getTable('madstack_storestatus')} (
              `sno` int(100) unsigned NOT NULL auto_increment,
              `store_id` int(100) NOT NULL ,
              `license_valid` int(11) NOT NULL ,
              `carousal_enable` int(11) NOT NULL ,
              `digestion_status` varchar(25) NULL default '',
              `created_time` datetime NULL,
              `updated_time`  datetime NULL,
              PRIMARY KEY (`sno`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();